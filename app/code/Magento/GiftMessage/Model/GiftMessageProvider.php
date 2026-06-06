<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\GiftMessage\Model;

use Magento\Framework\DataObject;
use Magento\GiftMessage\Helper\Message;
use Magento\Sales\Model\GiftMessage\GiftMessageProviderInterface;

/**
 * Real gift-message provider bound by Magento_GiftMessage, delegating to Helper\Message.
 */
class GiftMessageProvider implements GiftMessageProviderInterface
{
    /**
     * @param Message $messageHelper
     */
    public function __construct(
        private readonly Message $messageHelper
    ) {
    }

    /**
     * @inheritDoc
     */
    public function isMessagesAllowed($type, DataObject $entity, $store = null)
    {
        return $this->messageHelper->isMessagesAllowed($type, $entity, $store);
    }

    /**
     * @inheritDoc
     */
    public function getGiftMessage($messageId = null)
    {
        return $this->messageHelper->getGiftMessage($messageId);
    }
}
