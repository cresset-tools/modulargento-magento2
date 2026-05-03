<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Catalog\Helper\Wishlist;

/**
 * Narrow seam used by Catalog (and CatalogWidget) to talk to Magento_Wishlist.
 *
 * Catalog ships a null implementation; Magento_Wishlist binds a real one.
 * This is what lets module-wishlist be removed without breaking
 * Magento_Catalog's DI (Catalog\Helper\Product\Compare and
 * Catalog\Block\Product\Context previously took Wishlist\Helper\Data as a
 * required, typed constructor argument).
 *
 * @api
 */
interface AddToWishlistInterface
{
    /**
     * Whether adding to wishlist is enabled (config + permission).
     */
    public function isAllow(): bool;

    /**
     * Serialized post-data for the storefront "add to wishlist" form.
     *
     * @param \Magento\Catalog\Model\Product $product
     * @param array $params
     * @return string
     */
    public function getAddParams($product, array $params = []): string;
}
