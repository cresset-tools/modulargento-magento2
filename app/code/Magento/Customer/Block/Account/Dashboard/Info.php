<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Customer\Block\Account\Dashboard;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Block\Account\Newsletter\SubscriptionStatusProviderInterface;
use Magento\Customer\Block\Form\Register;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Customer\Helper\View;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Dashboard Customer Info
 *
 * @api
 * @since 100.0.2
 */
class Info extends Template
{
    /**
     * @var SubscriptionStatusProviderInterface
     */
    protected $subscriptionStatusProvider;

    /**
     * @var View
     */
    protected $_helperView;

    /**
     * @var CurrentCustomer
     */
    protected $currentCustomer;

    /**
     * Constructor
     *
     * @param Context $context
     * @param CurrentCustomer $currentCustomer
     * @param SubscriptionStatusProviderInterface $subscriptionStatusProvider
     * @param View $helperView
     * @param array $data
     */
    public function __construct(
        Context $context,
        CurrentCustomer $currentCustomer,
        SubscriptionStatusProviderInterface $subscriptionStatusProvider,
        View $helperView,
        array $data = []
    ) {
        $this->currentCustomer = $currentCustomer;
        $this->subscriptionStatusProvider = $subscriptionStatusProvider;
        $this->_helperView = $helperView;
        parent::__construct($context, $data);
    }

    /**
     * Returns the Magento Customer Model for this block
     *
     * @return CustomerInterface|null
     */
    public function getCustomer()
    {
        try {
            return $this->currentCustomer->getCustomer();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * Get the full name of a customer
     *
     * @return string full name
     */
    public function getName()
    {
        return $this->_helperView->getCustomerName($this->getCustomer());
    }

    /**
     * Get the url to change password
     *
     * @return string
     */
    public function getChangePasswordUrl()
    {
        return $this->_urlBuilder->getUrl('customer/account/edit/changepass/1');
    }

    /**
     * Gets Customer subscription status
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getIsSubscribed()
    {
        $customer = $this->getCustomer();
        if (!$customer) {
            return false;
        }
        $websiteId = (int)$this->_storeManager->getWebsite()->getId();
        return $this->subscriptionStatusProvider->isSubscribed((int)$customer->getId(), $websiteId);
    }

    /**
     * Newsletter module availability
     *
     * @return bool
     */
    public function isNewsletterEnabled()
    {
        return $this->getLayout()
            ->getBlockSingleton(Register::class)
            ->isNewsletterEnabled();
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml()
    {
        return $this->currentCustomer->getCustomerId() ? parent::_toHtml() : '';
    }
}
