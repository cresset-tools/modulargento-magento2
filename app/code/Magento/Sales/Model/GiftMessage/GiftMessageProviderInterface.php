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
}
