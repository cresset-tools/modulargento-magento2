<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Catalog\Model\Product;

/**
 * Supplies the MSRP ("minimum advertised price") display decisions that catalog
 * and checkout blocks need, without those modules hard-depending on Magento_Msrp.
 * Magento_Msrp binds the real implementation (wrapping Magento\Msrp\Helper\Data);
 * the null default (NullMsrpDisplayProvider) reports MSRP as not applicable so
 * prices render normally when Msrp is not installed.
 */
interface MsrpDisplayProviderInterface
{
    /**
     * Whether MAP applies to the product (price must be hidden / gated).
     *
     * @param int|\Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function canApplyMsrp($product): bool;

    /**
     * Whether the product's MAP price must be revealed only before order confirmation.
     *
     * @param int|\Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function isShowBeforeOrderConfirm($product): bool;

    /**
     * Whether any MAP price is larger than the product's "as low as" value.
     *
     * @param int|\Magento\Catalog\Model\Product $product
     * @return bool
     */
    public function isMinimalPriceLessMsrp($product): bool;
}
