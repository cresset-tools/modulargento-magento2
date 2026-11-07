<?php
/**
 * Copyright 2017 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\ConfigurableProduct\Test\Unit\Pricing\Render;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\MsrpDisplayProviderInterface;
use Magento\Catalog\Model\Product\Pricing\Renderer\SalableResolverInterface;
use Magento\Catalog\Pricing\Price\MinimalPriceCalculatorInterface;
use Magento\ConfigurableProduct\Pricing\Price\ConfigurableOptionsProviderInterface;
use Magento\ConfigurableProduct\Pricing\Render\TierPriceBox;
use Magento\Framework\Pricing\Price\PriceInterface;
use Magento\Framework\Pricing\Render\RendererPool;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Element\Template\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TierPriceBoxTest extends TestCase
{
    /**
     * @var Context|MockObject
     */
    private $context;

    /**
     * @var Product|MockObject
     */
    private $saleableItem;

    /**
     * @var PriceInterface|MockObject
     */
    private $price;

    /**
     * @var RendererPool|MockObject
     */
    private $rendererPool;

    /**
     * @var SalableResolverInterface|MockObject
     */
    private $salableResolver;

    /**
     * @var MinimalPriceCalculatorInterface|MockObject
     */
    private $minimalPriceCalculator;

    /**
     * @var ConfigurableOptionsProviderInterface|MockObject
     */
    private $configurableOptionsProvider;

    /**
     * @var MsrpDisplayProviderInterface|MockObject
     */
    private $msrpDisplayProvider;

    /**
     * @var TierPriceBox
     */
    private $model;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->context = $this->createPartialMock(Context::class, []);
        $this->saleableItem = $this->createPartialMock(Product::class, []);
        $this->price = $this->createMock(PriceInterface::class);
        $this->rendererPool = $this->createPartialMock(RendererPool::class, []);
        $this->salableResolver = $this->createPartialMock(SalableResolverInterface::class, ['isSalable']);
        $this->minimalPriceCalculator = $this->createMock(MinimalPriceCalculatorInterface::class);
        $this->configurableOptionsProvider = $this->createMock(ConfigurableOptionsProviderInterface::class);
        $this->msrpDisplayProvider = $this->createMock(MsrpDisplayProviderInterface::class);

        $this->model = (new ObjectManager($this))->getObject(
            TierPriceBox::class,
            [
                'context' => $this->context,
                'saleableItem' => $this->saleableItem,
                'price' => $this->price,
                'rendererPool' => $this->rendererPool,
                'salableResolver' => $this->salableResolver,
                'minimalPriceCalculator' => $this->minimalPriceCalculator,
                'configurableOptionsProvider' => $this->configurableOptionsProvider,
                'msrpDisplayProvider' => $this->msrpDisplayProvider,
            ]
        );
    }

    public function testToHtmlEmptyWhenMsrpPriceIsApplicable(): void
    {
        $this->msrpDisplayProvider->expects($this->once())
            ->method('canApplyMsrp')
            ->with($this->saleableItem)
            ->willReturn(true);
        $this->msrpDisplayProvider->expects($this->once())
            ->method('isMinimalPriceLessMsrp')
            ->with($this->saleableItem)
            ->willReturn(true);

        $result = $this->model->toHtml();
        $this->assertSame('', $result);
    }
}
