<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Alerts;

use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Default no-op provider used when Magento_ProductAlert is not installed.
 */
class NullAlertCustomerCollectionProvider implements AlertCustomerCollectionProviderInterface
{
    public function getStockAlertCollection(int $productId, int $websiteId): ?AbstractDb
    {
        return null;
    }

    public function getPriceAlertCollection(int $productId, int $websiteId): ?AbstractDb
    {
        return null;
    }
}
