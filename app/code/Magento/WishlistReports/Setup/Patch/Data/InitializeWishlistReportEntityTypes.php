<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\WishlistReports\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Reports\Model\Event;
use Magento\Reports\Setup\Patch\Data\InitializeReportEntityTypesAndPages;

class InitializeWishlistReportEntityTypes implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $rows = [
            [
                'event_type_id' => Event::EVENT_PRODUCT_TO_WISHLIST,
                'event_name' => 'wishlist_add_product',
            ],
            [
                'event_type_id' => Event::EVENT_WISHLIST_SHARE,
                'event_name' => 'wishlist_share',
            ],
        ];

        $table = $this->moduleDataSetup->getTable('report_event_types');
        foreach ($rows as $row) {
            $this->moduleDataSetup->getConnection()->insertOnDuplicate($table, $row, ['event_name']);
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        return [
            InitializeReportEntityTypesAndPages::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        return [];
    }
}
