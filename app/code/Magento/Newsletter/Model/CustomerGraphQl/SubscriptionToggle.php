<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Newsletter\Model\CustomerGraphQl;

use Magento\CustomerGraphQl\Model\Customer\Newsletter\SubscriptionToggleInterface;
use Magento\Newsletter\Model\SubscriptionManagerInterface;

class SubscriptionToggle implements SubscriptionToggleInterface
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
