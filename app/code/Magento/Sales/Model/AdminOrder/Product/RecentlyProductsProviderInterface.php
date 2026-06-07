<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Sales\Model\AdminOrder\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

/**
 * Supplies the admin order-create sidebar with the customer's recently viewed /
 * compared products.
 *
 * The data comes from Magento_Reports' event log; this seam lets the sidebar
 * blocks live in Magento_Sales without a hard dependency on Magento_Reports.
 * The default (empty) implementation makes the sidebar simply show no recently
 * viewed/compared items when Reports is absent; Reports binds the real provider.
 */
interface RecentlyProductsProviderInterface
{
    /**
     * Product ids the customer recently viewed, across the given store ids.
     *
     * @param int[] $storeIds
     * @param int $customerId
     * @return int[]
     */
    public function getRecentlyViewedProductIds(array $storeIds, int $customerId): array;

    /**
     * Filter a product collection down to the customer's recently compared products.
     *
     * @param ProductCollection $productCollection
     * @param int $customerId
     * @param int[] $skipProductIds
     * @return void
     */
    public function applyRecentlyComparedFilter(
        ProductCollection $productCollection,
        int $customerId,
        array $skipProductIds
    ): void;
}
