<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\GiftMessage\Model;

use Magento\Framework\DataObject;
use Magento\GiftMessage\Helper\Message;
use Magento\GiftMessage\Model\MessageFactory;
use Magento\GiftMessage\Model\Save;
use Magento\Sales\Model\GiftMessage\GiftMessageProviderInterface;

/**
 * Real gift-message provider bound by Magento_GiftMessage, delegating to Helper\Message.
 */
class GiftMessageProvider implements GiftMessageProviderInterface
{
    /**
     * @param Message $messageHelper
     * @param Save $giftMessageSave
     * @param MessageFactory $messageFactory
     */
    public function __construct(
        private readonly Message $messageHelper,
        private readonly Save $giftMessageSave,
        private readonly MessageFactory $messageFactory
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

    /**
     * @inheritDoc
     */
    public function getIsAllowedQuoteItem($item)
    {
        return $this->giftMessageSave->getIsAllowedQuoteItem($item);
    }

    /**
     * @inheritDoc
     */
    public function getEntityModelByType($type)
    {
        return $this->messageFactory->create()->getEntityModelByType($type);
    }

    /**
     * @inheritDoc
     */
    public function getGiftMessageForEntity(DataObject $entity)
    {
        return $this->messageHelper->getGiftMessageForEntity($entity);
    }

    /**
     * @inheritDoc
     */
    public function getEscapedGiftMessage(DataObject $entity)
    {
        return $this->messageHelper->getEscapedGiftMessage($entity);
    }

    /**
     * @inheritDoc
     */
    public function saveGiftmessagesInQuote($giftmessages)
    {
        $this->giftMessageSave->setGiftmessages($giftmessages)->saveAllInQuote();
    }

    /**
     * @inheritDoc
     */
    public function saveGiftmessagesInOrder($giftmessages)
    {
        $this->giftMessageSave->setGiftmessages($giftmessages)->saveAllInOrder();
        return (bool)$this->giftMessageSave->getSaved();
    }

    /**
     * @inheritDoc
     */
    public function importAllowQuoteItemsFromProducts($products)
    {
        $this->giftMessageSave->importAllowQuoteItemsFromProducts($products);
    }

    /**
     * @inheritDoc
     */
    public function importAllowQuoteItemsFromItems($items)
    {
        $this->giftMessageSave->importAllowQuoteItemsFromItems($items);
    }
}
