<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\QuoteGraphQl\Model\CartItem\DataProvider;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Quote\Api\CartItemRepositoryInterface;
use Magento\Quote\Model\Quote;
use Magento\QuoteGraphQl\Model\Cart\UpdateCartItem;
use Magento\QuoteGraphQl\Model\CartItem\GiftMessageCartItemUpdaterInterface;

/**
 * Class contain update cart items methods
 */
class UpdateCartItems
{
    /**
     * UpdateCartItems Constructor
     *
     * @param CartItemRepositoryInterface $cartItemRepository
     * @param UpdateCartItem $updateCartItem
     * @param GiftMessageCartItemUpdaterInterface $giftMessageUpdater
     */
    public function __construct(
        private readonly CartItemRepositoryInterface $cartItemRepository,
        private readonly UpdateCartItem $updateCartItem,
        private readonly GiftMessageCartItemUpdaterInterface $giftMessageUpdater
    ) {
    }

    /**
     * Process cart items
     *
     * @param Quote $cart
     * @param array $items
     *
     * @throws GraphQlInputException
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function processCartItems(Quote $cart, array $items): void
    {
        foreach ($items as $item) {
            if (empty($item['cart_item_id'])) {
                throw new GraphQlInputException(__('Required parameter "cart_item_id" for "cart_items" is missing.'));
            }

            $itemId = (int)$item['cart_item_id'];
            $customizableOptions = $item['customizable_options'] ?? [];
            $cartItem = $cart->getItemById($itemId);

            if ($cartItem && $cartItem->getParentItemId()) {
                throw new GraphQlInputException(__('Child items may not be updated.'));
            }

            if (count($customizableOptions) === 0 && !isset($item['quantity'])) {
                throw new GraphQlInputException(__('Required parameter "quantity" for "cart_items" is missing.'));
            }

            $quantity = (float)$item['quantity'];

            if ($quantity <= 0.0) {
                $cart->removeItem($itemId);
            } else {
                $this->updateCartItem->execute($cart, $itemId, $quantity, $customizableOptions);
            }

            if (!empty($item['gift_message'])) {
                $this->giftMessageUpdater->process($cart, $cartItem, $item, $itemId);
            }
        }
    }
}
