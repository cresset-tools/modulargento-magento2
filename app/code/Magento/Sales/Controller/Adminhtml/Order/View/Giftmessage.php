<?php
/**
 * Copyright 2013 Adobe
 * All Rights Reserved.
 */
namespace Magento\Sales\Controller\Adminhtml\Order\View;

/**
 * Adminhtml sales order view gift messages controller
 */
abstract class Giftmessage extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Magento_Sales::sales_order';

    /**
     * Retrieve gift message provider seam (a null no-op when Magento_GiftMessage is absent)
     *
     * @return \Magento\Sales\Model\GiftMessage\GiftMessageProviderInterface
     */
    protected function _getGiftmessageSaveModel()
    {
        return $this->_objectManager->get(\Magento\Sales\Model\GiftMessage\GiftMessageProviderInterface::class);
    }
}
