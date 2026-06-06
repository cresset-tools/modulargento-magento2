<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\ProductAlert\Block\Adminhtml\Product\Edit\Tab\Alerts;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Tab\Alerts\AlertCustomerCollectionProviderInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\ProductAlert\Model\PriceFactory;
use Magento\ProductAlert\Model\StockFactory;

/**
 * Real provider for the admin product-edit Alerts grids, bound by Magento_ProductAlert.
 */
class AlertCustomerCollectionProvider implements AlertCustomerCollectionProviderInterface
{
    public function __construct(
        private readonly StockFactory $stockFactory,
        private readonly PriceFactory $priceFactory
    ) {
    }

    public function getStockAlertCollection(int $productId, int $websiteId): ?AbstractDb
    {
        return $this->stockFactory->create()->getCustomerCollection()->join($productId, $websiteId);
    }

    public function getPriceAlertCollection(int $productId, int $websiteId): ?AbstractDb
    {
        return $this->priceFactory->create()->getCustomerCollection()->join($productId, $websiteId);
    }
}
