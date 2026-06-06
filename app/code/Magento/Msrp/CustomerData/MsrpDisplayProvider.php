<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Msrp\CustomerData;

use Magento\Catalog\Model\Product\MsrpDisplayProviderInterface;
use Magento\Msrp\Helper\Data;

/**
 * Real MSRP display provider bound by Magento_Msrp, delegating to the Msrp helper
 * that the catalog/checkout consumers previously depended on directly.
 */
class MsrpDisplayProvider implements MsrpDisplayProviderInterface
{
    /**
     * @param Data $msrpHelper
     */
    public function __construct(
        private readonly Data $msrpHelper
    ) {
    }

    /**
     * @inheritDoc
     */
    public function canApplyMsrp($product): bool
    {
        return (bool)$this->msrpHelper->canApplyMsrp($product);
    }

    /**
     * @inheritDoc
     */
    public function isShowBeforeOrderConfirm($product): bool
    {
        return (bool)$this->msrpHelper->isShowBeforeOrderConfirm($product);
    }

    /**
     * @inheritDoc
     */
    public function isMinimalPriceLessMsrp($product): bool
    {
        return (bool)$this->msrpHelper->isMinimalPriceLessMsrp($product);
    }
}
