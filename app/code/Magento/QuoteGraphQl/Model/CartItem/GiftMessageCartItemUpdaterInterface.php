<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\QuoteGraphQl\Model\CartItem;

use Magento\Quote\Model\Quote;

/**
 * Applies the `gift_message` field of a cart-item update.
 *
 * Seam that keeps Magento_QuoteGraphQl free of a hard dependency on
 * Magento_GiftMessage / Magento_GiftMessageGraphQl. The null default is a no-op
 * (gift messages are simply ignored when the modules are absent); when present,
 * Magento_GiftMessageGraphQl binds the real implementation in the graphql area.
 */
interface GiftMessageCartItemUpdaterInterface
{
    /**
     * Persist the gift message carried by a cart-item update payload, if any.
     *
     * @param Quote $cart
     * @param mixed $cartItem The resolved quote item (Magento\Quote\Model\Quote\Item|false|null)
     * @param array $item The raw cart-item input, including the optional 'gift_message' key
     * @param int $itemId
     * @return void
     */
    public function process(Quote $cart, $cartItem, array $item, int $itemId): void;
}
