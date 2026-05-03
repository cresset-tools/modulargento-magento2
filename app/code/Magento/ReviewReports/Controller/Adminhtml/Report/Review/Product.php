<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\ReviewReports\Controller\Adminhtml\Report\Review;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;

class Product extends \Magento\ReviewReports\Controller\Adminhtml\Report\Review implements HttpGetActionInterface
{
    /**
     * Product reviews report action
     *
     * @return void
     */
    public function execute()
    {
        $this->_initAction()->_setActiveMenu(
            'Magento_Review::report_review_product'
        )->_addBreadcrumb(
            __('Products Report'),
            __('Products Report')
        );
        $this->_view->getPage()->getConfig()->getTitle()->prepend(__('Product Reviews Report'));
        $this->_view->renderLayout();
    }
}
