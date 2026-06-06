<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Sales\Model\GiftMessage;

use Magento\Framework\DataObject;

/**
 * Gift-message access seam for admin sales blocks, so Magento_Sales (and modules
 * extending its blocks, e.g. Magento_Bundle) don't hard-depend on
 * Magento_GiftMessage. Magento_GiftMessage binds the real implementation
 * (wrapping its Helper\Message); the null default reports "not allowed" / no
 * message so the gift-message UI is simply hidden when the module is absent.
 */
interface GiftMessageProviderInterface
{
    /**
     * Whether gift messages are allowed for the given entity/type.
     *
     * @param string $type
     * @param DataObject $entity
     * @param null|int|string|\Magento\Store\Model\Store $store
     * @return bool
     */
    public function isMessagesAllowed($type, DataObject $entity, $store = null);

    /**
     * Load a gift message by id (null/empty when gift messages are unavailable).
     *
     * @param int|null $messageId
     * @return \Magento\Framework\DataObject|null
     */
    public function getGiftMessage($messageId = null);

    /**
     * Whether the given quote item is allowed to carry a gift message.
     *
     * @param \Magento\Framework\DataObject $item
     * @return bool
     */
    public function getIsAllowedQuoteItem($item);

    /**
     * Create the gift-message entity model for the given entity type (e.g. 'order').
     *
     * Returns null when gift messages are unavailable. The concrete return type is
     * intentionally left unbound so Magento_Sales carries no reference to
     * Magento_GiftMessage classes.
     *
     * @param string $type
     * @return mixed|null
     */
    public function getEntityModelByType($type);

    /**
     * Load the gift message attached to the given entity (order/item), or null.
     *
     * @param DataObject $entity
     * @return \Magento\Framework\DataObject|null
     */
    public function getGiftMessageForEntity(DataObject $entity);

    /**
     * Escaped, line-broken gift-message text for the given entity ('' when none).
     *
     * @param DataObject $entity
     * @return string
     */
    public function getEscapedGiftMessage(DataObject $entity);

    /**
     * Persist the posted gift messages against the current admin quote (no-op when unavailable).
     *
     * @param array $giftmessages
     * @return void
     */
    public function saveGiftmessagesInQuote($giftmessages);

    /**
     * Persist the posted gift messages against the current admin order.
     *
     * @param array $giftmessages
     * @return bool Whether anything was saved.
     */
    public function saveGiftmessagesInOrder($giftmessages);

    /**
     * Import the "gift message allowed" flags for quote items from an add-products payload.
     *
     * @param array $products
     * @return void
     */
    public function importAllowQuoteItemsFromProducts($products);

    /**
     * Import the "gift message allowed" flags for quote items from an update-items payload.
     *
     * @param array $items
     * @return void
     */
    public function importAllowQuoteItemsFromItems($items);
}
