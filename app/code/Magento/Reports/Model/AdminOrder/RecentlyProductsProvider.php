<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Reports\Model\AdminOrder;

use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Reports\Model\Event;
use Magento\Reports\Model\EventFactory;
use Magento\Reports\Model\ResourceModel\Event as EventResource;
use Magento\Sales\Model\AdminOrder\Product\RecentlyProductsProviderInterface;

/**
 * Real provider backed by the Magento_Reports event log — supplies the admin
 * order-create sidebar's recently viewed / compared products.
 */
class RecentlyProductsProvider implements RecentlyProductsProviderInterface
{
    /**
     * @var EventFactory
     */
    private $eventFactory;

    /**
     * @var EventResource
     */
    private $eventResource;

    /**
     * @param EventFactory $eventFactory
     * @param EventResource $eventResource
     */
    public function __construct(EventFactory $eventFactory, EventResource $eventResource)
    {
        $this->eventFactory = $eventFactory;
        $this->eventResource = $eventResource;
    }

    /**
     * @inheritdoc
     */
    public function getRecentlyViewedProductIds(array $storeIds, int $customerId): array
    {
        $collection = $this->eventFactory->create()->getCollection()
            ->addStoreFilter($storeIds)
            ->addRecentlyFiler(Event::EVENT_PRODUCT_VIEW, $customerId, 0);

        $productIds = [];
        foreach ($collection as $event) {
            $productIds[] = $event->getObjectId();
        }

        return $productIds;
    }

    /**
     * @inheritdoc
     */
    public function applyRecentlyComparedFilter(
        ProductCollection $productCollection,
        int $customerId,
        array $skipProductIds
    ): void {
        $this->eventResource->applyLogToCollection(
            $productCollection,
            Event::EVENT_PRODUCT_COMPARE,
            $customerId,
            0,
            $skipProductIds
        );
    }
}
