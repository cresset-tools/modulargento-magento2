<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Sales\Model\AdminOrder\Product;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;

/**
 * Default provider used when Magento_Reports is absent: there is no view/compare
 * event log, so the recently viewed/compared sidebar sections are empty.
 */
class EmptyRecentlyProductsProvider implements RecentlyProductsProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getRecentlyViewedProductIds(array $storeIds, int $customerId): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function applyRecentlyComparedFilter(
        ProductCollection $productCollection,
        int $customerId,
        array $skipProductIds
    ): void {
        // No compare-event log without Magento_Reports — yield an empty result.
        $productCollection->addIdFilter([0]);
    }
}
