<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\QuoteGraphQl\Model\CartItem;

use Magento\Quote\Model\Quote;

/**
 * Default no-op updater used when Magento_GiftMessageGraphQl is not installed.
 */
class NullGiftMessageCartItemUpdater implements GiftMessageCartItemUpdaterInterface
{
    /**
     * @inheritDoc
     */
    public function process(Quote $cart, $cartItem, array $item, int $itemId): void
    {
    }
}
