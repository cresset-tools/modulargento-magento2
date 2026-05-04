<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Newsletter\Model\Customer;

use Magento\Customer\Controller\Adminhtml\Newsletter\AdminSubscriptionToggleInterface;
use Magento\Newsletter\Model\SubscriptionManagerInterface;

class AdminSubscriptionToggle implements AdminSubscriptionToggleInterface
{
    public function __construct(
        private readonly SubscriptionManagerInterface $subscriptionManager
    ) {
    }

    public function apply(int $customerId, int $storeId, bool $subscribed): void
    {
        if ($subscribed) {
            $this->subscriptionManager->subscribeCustomer($customerId, $storeId);
        } else {
            $this->subscriptionManager->unsubscribeCustomer($customerId, $storeId);
        }
    }
}
