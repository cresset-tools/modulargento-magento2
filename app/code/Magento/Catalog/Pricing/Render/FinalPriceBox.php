<?php
/**
 * Copyright 2014 Adobe
 * All Rights Reserved.
 */

namespace Magento\Catalog\Pricing\Render;

use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\PriceBox as BasePriceBox;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\Pricing\SaleableInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\Product\MsrpDisplayProviderInterface;

/**
 * Class for final_price rendering
 *
 * @method bool getUseLinkForAsLowAs()
 * @method bool getDisplayMinimalPrice()
 */
class FinalPriceBox extends BasePriceBox
{
    /**
     * @var SalableResolverInterface
     */
    private $salableResolver;

    /**
     * @var MinimalPriceCalculatorInterface
     */
    private $minimalPriceCalculator;

    /**
     * @var MsrpDisplayProviderInterface
     */
    private $msrpDisplayProvider;

    /**
     * @param Context $context
     * @param SaleableInterface $saleableItem
     * @param PriceInterface $price
     * @param RendererPool $rendererPool
     * @param array $data
     * @param SalableResolverInterface $salableResolver
     * @param MinimalPriceCalculatorInterface $minimalPriceCalculator
     * @param MsrpDisplayProviderInterface $msrpDisplayProvider
     */
    public function __construct(
        Context $context,
        SaleableInterface $saleableItem,
        PriceInterface $price,
        RendererPool $rendererPool,
        array $data = [],
        ?SalableResolverInterface $salableResolver = null,
        ?MinimalPriceCalculatorInterface $minimalPriceCalculator = null,
        ?MsrpDisplayProviderInterface $msrpDisplayProvider = null
    ) {
        parent::__construct($context, $saleableItem, $price, $rendererPool, $data);
        $this->salableResolver = $salableResolver ?: ObjectManager::getInstance()->get(SalableResolverInterface::class);
        $this->minimalPriceCalculator = $minimalPriceCalculator
            ?: ObjectManager::getInstance()->get(MinimalPriceCalculatorInterface::class);
        $this->msrpDisplayProvider = $msrpDisplayProvider
            ?: ObjectManager::getInstance()->get(MsrpDisplayProviderInterface::class);
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml()
    {
        if (!$this->salableResolver->isSalable($this->getSaleableItem())) {
            return '';
        }

        $result = parent::_toHtml();
        //Renders MSRP in case it is enabled
        if ($this->isMsrpPriceApplicable()) {
            /** @var BasePriceBox $msrpBlock */
            $msrpBlock = $this->rendererPool->createPriceRender(
                'msrp_price',
                $this->getSaleableItem(),
                [
                    'real_price_html' => $result,
                    'zone' => $this->getZone(),
                ]
            );
            $result = $msrpBlock->toHtml();
        }

        return $this->wrapResult($result);
    }

    /**
     * Check is MSRP applicable for the current product.
     *
     * @return bool
     */
    protected function isMsrpPriceApplicable()
    {
        // Routed through MsrpDisplayProviderInterface (the Null default reports
        // "not applicable" when Magento_Msrp isn't installed) instead of looking
        // up the 'msrp_price' price type directly — that type is only registered
        // in the price pool by Magento_Msrp, so requesting it in a build without
        // Msrp produced a fatal "Class \"\" does not exist" from the price
        // factory rather than a catchable miss.
        $product = $this->getSaleableItem();

        return $this->msrpDisplayProvider->canApplyMsrp($product)
            && $this->msrpDisplayProvider->isMinimalPriceLessMsrp($product);
    }

    /**
     * Wrap with standard required container
     *
     * @param string $html
     * @return string
     */
    protected function wrapResult($html)
    {
        return '<div class="price-box ' . $this->getData('css_classes') . '" ' .
            'data-role="priceBox" ' .
            'data-product-id="' . $this->getSaleableItem()->getId() . '" ' .
            'data-price-box="product-id-' . $this->getSaleableItem()->getId() . '"' .
            '>' . $html . '</div>';
    }

    /**
     * Render minimal amount
     *
     * @return string
     */
    public function renderAmountMinimal()
    {
        $id = $this->getPriceId() ? $this->getPriceId() : 'product-minimal-price-' . $this->getSaleableItem()->getId();

        $amount = $this->minimalPriceCalculator->getAmount($this->getSaleableItem());
        if ($amount === null) {
            return '';
        }

        return $this->renderAmount(
            $amount,
            [
                'display_label'     => __('As low as'),
                'price_id'          => $id,
                'include_container' => false,
                'skip_adjustments' => false
            ]
        );
    }

    /**
     * Define if the special price should be shown
     *
     * @return bool
     */
    public function hasSpecialPrice()
    {
        if ($this->isProductList()) {
            if (!$this->getData('special_price_map')) {
                return false;
            }

            return (bool)$this->getData('special_price_map')[$this->saleableItem->getId()];
        } else {
            $displayRegularPrice = $this->getPriceType(Price\RegularPrice::PRICE_CODE)->getAmount()->getValue();
            $displayFinalPrice = $this->getPriceType(Price\FinalPrice::PRICE_CODE)->getAmount()->getValue();

            return $displayFinalPrice < $displayRegularPrice;
        }
    }

    /**
     * Define if the minimal price should be shown
     *
     * @return bool
     */
    public function showMinimalPrice()
    {
        $minTierPrice = $this->minimalPriceCalculator->getValue($this->getSaleableItem());

        /** @var Price\FinalPrice $finalPrice */
        $finalPrice = $this->getPriceType(Price\FinalPrice::PRICE_CODE);
        $finalPriceValue = $finalPrice->getAmount()->getValue();

        return $this->getDisplayMinimalPrice()
            && $minTierPrice !== null
            && $minTierPrice < $finalPriceValue;
    }

    /**
     * Get Key for caching block content
     *
     * @return string
     */
    public function getCacheKey()
    {
        return parent::getCacheKey()
            . ($this->getData('list_category_page') ? '-list-category-page' : '')
            . ($this->getSaleableItem()->getCustomerGroupId() ?? '');
    }

    /**
     * @inheritdoc
     */
    public function getCacheKeyInfo()
    {
        $cacheKeys = parent::getCacheKeyInfo();
        $cacheKeys['display_minimal_price'] = $this->getDisplayMinimalPrice();
        $cacheKeys['is_product_list'] = $this->isProductList();
        $cacheKeys['customer_group_id'] = $this->getSaleableItem()->getCustomerGroupId();
        $cacheKeys['zone'] = $this->getZone();
        return $cacheKeys;
    }

    /**
     * Get flag that price rendering should be done for the list of products.
     *
     * By default (if flag is not set) is false.
     *
     * @return bool
     */
    public function isProductList()
    {
        $isProductList = $this->getData('is_product_list');
        return $isProductList === true;
    }
}
