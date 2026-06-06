<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Alerts;

use Magento\Framework\Data\Collection\AbstractDb;

/**
 * Supplies the "customers subscribed to a product alert" grid collections for the
 * admin product-edit Alerts tabs, without Magento_Catalog hard-depending on
 * Magento_ProductAlert. Magento_ProductAlert binds the real implementation; the
 * null default (NullAlertCustomerCollectionProvider) returns null so the grids
 * render empty when ProductAlert is not installed.
 */
interface AlertCustomerCollectionProviderInterface
{
    /**
     * @param int $productId
     * @param int $websiteId
     * @return AbstractDb|null
     */
    public function getStockAlertCollection(int $productId, int $websiteId): ?AbstractDb;

    /**
     * @param int $productId
     * @param int $websiteId
     * @return AbstractDb|null
     */
    public function getPriceAlertCollection(int $productId, int $websiteId): ?AbstractDb;
}
