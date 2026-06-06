<?php
/**
 * Copyright 2017 Adobe
 * All Rights Reserved.
 */

namespace Magento\Setup\Model\FixtureGenerator;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\App\ObjectManager;

/**
 * Bundle product template generator. Return newly created bundle product for specified attribute set
 * with default values for product attributes
 */
class BundleProductTemplateGenerator implements TemplateEntityGeneratorInterface
{
    /**
     * @var array
     */
    private $fixture;

    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var \Magento\Bundle\Api\Data\OptionInterfaceFactory|null
     */
    private $optionFactory;

    /**
     * @var \Magento\Bundle\Api\Data\LinkInterfaceFactory|null
     */
    private $linkFactory;

    /**
     * @param ProductFactory $productFactory
     * @param array $fixture
     */
    public function __construct(
        ProductFactory $productFactory,
        array $fixture
    ) {
        $this->fixture = $fixture;
        $this->productFactory = $productFactory;
    }

    /**
     * Bundle option/link factories, resolved lazily — only used while generating bundle
     * fixtures, which cannot happen when Magento_Bundle is removed, so the (removable)
     * Bundle\Api classes are never referenced in that case.
     *
     * @return \Magento\Bundle\Api\Data\OptionInterfaceFactory
     */
    private function getOptionFactory()
    {
        if ($this->optionFactory === null) {
            $this->optionFactory = ObjectManager::getInstance()
                ->get(\Magento\Bundle\Api\Data\OptionInterfaceFactory::class);
        }
        return $this->optionFactory;
    }

    /**
     * @return \Magento\Bundle\Api\Data\LinkInterfaceFactory
     */
    private function getLinkFactory()
    {
        if ($this->linkFactory === null) {
            $this->linkFactory = ObjectManager::getInstance()
                ->get(\Magento\Bundle\Api\Data\LinkInterfaceFactory::class);
        }
        return $this->linkFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function generateEntity()
    {
        $product = $this->getProductTemplate(
            $this->fixture['attribute_set_id']
        );
        $product->save();

        return $product;
    }

    /**
     * Get product template
     *
     * @param int $attributeSet
     * @return ProductInterface
     */
    private function getProductTemplate($attributeSet)
    {
        $bundleOptions = $this->fixture['_bundle_options'];
        $bundleProductsPerOption = $this->fixture['_bundle_products_per_option'];
        $bundleVariationSkuPattern = $this->fixture['_bundle_variation_sku_pattern'];
        $productRandomizerNumber = crc32(random_int(1, PHP_INT_MAX));
        $bundleProduct = $this->productFactory->create([
            'data' => [
                'attribute_set_id' => $attributeSet,
                'type_id' => Type::TYPE_BUNDLE,
                'name' => 'template name' . $productRandomizerNumber,
                'url_key' => 'template-url' . $productRandomizerNumber,
                'sku' => 'template_sku_bundle' . $productRandomizerNumber,
                'price' => 10,
                'visibility' => Visibility::VISIBILITY_BOTH,
                'status' => Status::STATUS_ENABLED,
                'website_ids' => [1],
                'category_ids' => isset($this->fixture['category_ids']) ? [2] : null,
                'weight' => 1,
                'description' => 'description',
                'short_description' => 'short description',
                'tax_class_id' => 2, //'taxable goods',
                'price_type' => 1, // Magento\Bundle\Model\Product\Price::PRICE_TYPE_FIXED — inlined (Bundle removable)
                'price_view' => 1,
                'stock_data' => [
                    'use_config_manage_stock' => 1,
                    'qty' => 100500,
                    'is_qty_decimal' => 0,
                    'is_in_stock' => 1
                ],
                'can_save_bundle_selections' => true,
                'affect_bundle_product_selections' => true,

            ]
        ]);

        $bundleProductOptions = [];
        $variationN = 0;
        for ($i = 1; $i <= $bundleOptions; $i++) {
            $option = $this->getOptionFactory()->create(['data' => [
                'title' => 'Bundle Product Items ' . $i,
                'default_title' => 'Bundle Product Items ' . $i,
                'type' => 'select',
                'required' => 1,
                'delete' => '',
                'position' => $bundleOptions - $i,
                'option_id' => '',
            ]]);
            $option->setSku($bundleProduct->getSku());

            $links = [];
            for ($linkN = 1; $linkN <= $bundleProductsPerOption; $linkN++) {
                $variationN++;
                $link = $this->getLinkFactory()->create(['data' => [
                    'sku' => sprintf($bundleVariationSkuPattern, $variationN),
                    'qty' => 1,
                    'can_change_qty' => 1,
                    'position' => $linkN - 1,
                    'price_type' => 0,
                    'price' => 0.0,
                    'option_id' => '',
                    'is_default' => $linkN === 1,
                ]]);
                $links[] = $link;
            }
            $option->setProductLinks($links);
            $bundleProductOptions[] = $option;
        }

        $extension = $bundleProduct->getExtensionAttributes();
        $extension->setBundleProductOptions($bundleProductOptions);
        $bundleProduct->setExtensionAttributes($extension);
        // Need for set "has_options" field
        $bundleProduct->setBundleOptionsData($bundleProductOptions);
        $bundleSelections = array_map(function ($option) {
            return array_map(function ($link) {
                return $link->getData();
            }, $option->getProductLinks());
        }, $bundleProductOptions);
        $bundleProduct->setBundleSelectionsData($bundleSelections);

        return $bundleProduct;
    }
}
