<?php
/**
 * Copyright 2017 Adobe
 * All Rights Reserved.
 */
namespace Magento\Setup\Fixtures\AttributeSet;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;

/**
 * Generates data for creating Visual Swatch attributes of "image" and "color" types.
 */
class SwatchesGenerator
{
    /**
     * Generated swatch image width in pixels.
     *
     * @var int
     */
    const GENERATED_SWATCH_WIDTH = 110;

    /**
     * Generated swatch image height in pixels.
     *
     * @var int
     */
    const GENERATED_SWATCH_HEIGHT = 90;

    /**
     * File name for temporary swatch image file.
     *
     * @var string
     */
    const GENERATED_SWATCH_TMP_NAME = 'tmp_swatch.jpg';

    /**
     * Visual swatch input type — value of Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL.
     *
     * Inlined so this fixture carries no hard reference to Magento_Swatches and the
     * swatches set can be removed.
     *
     * @var string
     */
    private const SWATCH_INPUT_TYPE_VISUAL = 'visual';

    /**
     * @var \Magento\Swatches\Helper\Media|null
     */
    private $swatchHelper;

    /**
     * @var \Magento\Setup\Fixtures\ImagesGenerator\ImagesGeneratorFactory
     */
    private $imagesGeneratorFactory;

    /**
     * @var \Magento\Setup\Fixtures\ImagesGenerator\ImagesGenerator
     */
    private $imagesGenerator;

    /**
     * @param \Magento\Setup\Fixtures\ImagesGenerator\ImagesGeneratorFactory $imagesGeneratorFactory
     */
    public function __construct(
        \Magento\Setup\Fixtures\ImagesGenerator\ImagesGeneratorFactory $imagesGeneratorFactory
    ) {
        $this->imagesGeneratorFactory = $imagesGeneratorFactory;
    }

    /**
     * Resolve the Swatches media helper lazily.
     *
     * Generating visual-swatch *image* data requires Magento_Swatches; the helper is
     * resolved on demand (rather than via the constructor) so this performance-fixture
     * class compiles and loads even when the swatches set has been removed.
     *
     * @return \Magento\Swatches\Helper\Media
     */
    private function getSwatchHelper()
    {
        if ($this->swatchHelper === null) {
            $this->swatchHelper = ObjectManager::getInstance()
                ->get(\Magento\Swatches\Helper\Media::class);
        }
        return $this->swatchHelper;
    }

    /**
     * Generates data for Swatch Attribute of the required type
     *
     * @param int $optionCount
     * @param string $data
     * @param string $type
     * @return array
     */
    public function generateSwatchData($optionCount, $data, $type)
    {
        if ($type === null) {
            return [];
        }

        $attribute['swatch_input_type'] = self::SWATCH_INPUT_TYPE_VISUAL;
        $attribute['swatchvisual']['value'] = array_reduce(
            range(1, $optionCount),
            function ($values, $index) use ($optionCount, $data, $type) {
                if ($type === 'image') {
                    $values['option_' . $index] = $this->generateSwatchImage($data . $index);
                }
                if ($type === 'color') {
                    $values['option_' . $index] = $this->generateSwatchColor($index / $optionCount);
                }
                return $values;
            },
            []
        );
        $attribute['optionvisual']['value'] = array_reduce(
            range(1, $optionCount),
            function ($values, $index) {
                $values['option_' . $index] = ['option ' . $index];
                return $values;
            },
            []
        );

        return $attribute;
    }

    /**
     * Generate hex-coded color for Swatch Attribute based on provided index
     *
     * Colors will change gradually according to index value.
     *
     * @param int $index
     * @return string
     */
    private function generateSwatchColor($index)
    {
        return '#' . str_repeat(dechex(255 * $index), 3);
    }

    /**
     * Generate and save image for Swatch Attribute
     *
     * Image is generated with a set background color rgb(240, 240, 240), random foreground color, and pattern which
     * is based on the binary representation of $data.
     *
     * @param string $data String value to be used for generation.
     * @return string Path to the image file.
     */
    private function generateSwatchImage($data)
    {
        if ($this->imagesGenerator === null) {
            $this->imagesGenerator = $this->imagesGeneratorFactory->create();
        }

        // phpcs:ignore Magento2.Security.InsecureFunction
        $imageName = md5($data) . '.jpg';
        $this->imagesGenerator->generate([
            'image-width' => self::GENERATED_SWATCH_WIDTH,
            'image-height' => self::GENERATED_SWATCH_HEIGHT,
            'image-name' => $imageName
        ]);

        $swatchHelper = $this->getSwatchHelper();
        $imagePath = substr($swatchHelper->moveImageFromTmp($imageName), 1);
        $swatchHelper->generateSwatchVariations($imagePath);

        return $imagePath;
    }
}
