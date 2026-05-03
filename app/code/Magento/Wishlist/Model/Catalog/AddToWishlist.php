<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Wishlist\Model\Catalog;

use Magento\Catalog\Helper\Wishlist\AddToWishlistInterface;
use Magento\Wishlist\Helper\Data as WishlistHelper;

class AddToWishlist implements AddToWishlistInterface
{
    /**
     * @var WishlistHelper
     */
    private $wishlistHelper;

    public function __construct(WishlistHelper $wishlistHelper)
    {
        $this->wishlistHelper = $wishlistHelper;
    }

    public function isAllow(): bool
    {
        return (bool) $this->wishlistHelper->isAllow();
    }

    public function getAddParams($product, array $params = []): string
    {
        return (string) $this->wishlistHelper->getAddParams($product, $params);
    }
}
