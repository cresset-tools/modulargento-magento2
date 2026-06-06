<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Catalog\Model\Product;

/**
 * Default provider used when Magento_Msrp is not installed: MSRP never applies,
 * so catalog/checkout prices render as usual. Magento_Msrp overrides this
 * preference with the real implementation.
 */
class NullMsrpDisplayProvider implements MsrpDisplayProviderInterface
{
    /**
     * @inheritDoc
     */
    public function canApplyMsrp($product): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isShowBeforeOrderConfirm($product): bool
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function isMinimalPriceLessMsrp($product): bool
    {
        return false;
    }
}
