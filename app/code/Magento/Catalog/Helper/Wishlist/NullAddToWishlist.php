<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Catalog\Helper\Wishlist;

class NullAddToWishlist implements AddToWishlistInterface
{
    public function isAllow(): bool
    {
        return false;
    }

    public function getAddParams($product, array $params = []): string
    {
        return '';
    }
}
