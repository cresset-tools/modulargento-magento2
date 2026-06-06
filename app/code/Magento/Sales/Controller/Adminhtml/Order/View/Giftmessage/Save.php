<?php
/**
 * Copyright 2014 Adobe
 * All Rights Reserved.
 */
namespace Magento\Sales\Controller\Adminhtml\Order\View\Giftmessage;

class Save extends \Magento\Sales\Controller\Adminhtml\Order\View\Giftmessage
{
    /**
     * @return void
     */
    public function execute()
    {
        $saved = false;
        try {
            $saved = $this->_getGiftmessageSaveModel()->saveGiftmessagesInOrder(
                $this->getRequest()->getParam('giftmessage')
            );
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Something went wrong while saving the gift message.'));
        }

        if ($this->getRequest()->getParam('type') == 'order_item') {
            $this->getResponse()->setBody($saved ? 'YES' : 'NO');
        } else {
            $this->getResponse()->setBody(__('You saved the gift card message.'));
        }
    }
}
