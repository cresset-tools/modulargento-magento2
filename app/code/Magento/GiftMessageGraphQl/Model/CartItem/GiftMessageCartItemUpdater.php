<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\GiftMessageGraphQl\Model\CartItem;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\GiftMessage\Api\Data\MessageInterface;
use Magento\GiftMessage\Api\Data\MessageInterfaceFactory;
use Magento\GiftMessage\Api\ItemRepositoryInterface;
use Magento\GiftMessageGraphQl\Model\Config\Messages;
use Magento\Quote\Model\Quote;
use Magento\QuoteGraphQl\Model\CartItem\GiftMessageCartItemUpdaterInterface;

/**
 * Real cart-item gift-message updater, bound by Magento_GiftMessageGraphQl.
 *
 * Holds the Magento_GiftMessage dependencies that Magento_QuoteGraphQl must not
 * reference directly, so the gift-message set can be removed cleanly.
 */
class GiftMessageCartItemUpdater implements GiftMessageCartItemUpdaterInterface
{
    /**
     * @param ItemRepositoryInterface $itemRepository
     * @param Messages $messagesConfig
     * @param MessageInterfaceFactory $giftMessageFactory
     */
    public function __construct(
        private readonly ItemRepositoryInterface $itemRepository,
        private readonly Messages $messagesConfig,
        private readonly MessageInterfaceFactory $giftMessageFactory
    ) {
    }

    /**
     * @inheritDoc
     *
     * @throws GraphQlInputException
     */
    public function process(Quote $cart, $cartItem, array $item, int $itemId): void
    {
        if (empty($item['gift_message'])) {
            return;
        }

        try {
            if (!$this->messagesConfig->isMessagesAllowed('items', $cartItem)) {
                return;
            }
            if (!$this->messagesConfig->isMessagesAllowed('item', $cartItem)) {
                return;
            }

            /** @var MessageInterface $giftItemMessage */
            $giftItemMessage = $this->itemRepository->get($cart->getEntityId(), $itemId);

            if (!$giftItemMessage) {
                /** @var MessageInterface $giftMessage */
                $giftMessage = $this->giftMessageFactory->create();
                $this->updateGiftMessageForItem($cart, $giftMessage, $item, $itemId);
                return;
            }
        } catch (LocalizedException $exception) {
            throw new GraphQlInputException(__('Gift Message cannot be updated.'));
        }

        $this->updateGiftMessageForItem($cart, $giftItemMessage, $item, $itemId);
    }

    /**
     * Update Gift Message for Quote item
     *
     * @param Quote $cart
     * @param MessageInterface $giftItemMessage
     * @param array $item
     * @param int $itemId
     * @return void
     * @throws GraphQlInputException
     */
    private function updateGiftMessageForItem(
        Quote $cart,
        MessageInterface $giftItemMessage,
        array $item,
        int $itemId
    ): void {
        try {
            $giftItemMessage->setRecipient($item['gift_message']['to']);
            $giftItemMessage->setSender($item['gift_message']['from']);
            $giftItemMessage->setMessage($item['gift_message']['message']);
            $this->itemRepository->save($cart->getEntityId(), $giftItemMessage, $itemId);
        } catch (LocalizedException $exception) {
            throw new GraphQlInputException(__('Gift Message cannot be updated'));
        }
    }
}
