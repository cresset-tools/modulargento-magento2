<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Sales\Model\GiftMessage;

use Magento\Framework\DataObject;

/**
 * Default no-op provider used when Magento_GiftMessage is not installed.
 */
class NullGiftMessageProvider implements GiftMessageProviderInterface
{
    public function isMessagesAllowed($type, DataObject $entity, $store = null)
    {
        return false;
    }

    public function getGiftMessage($messageId = null)
    {
        return null;
    }

    public function getIsAllowedQuoteItem($item)
    {
        return false;
    }

    public function getEntityModelByType($type)
    {
        return null;
    }
}
