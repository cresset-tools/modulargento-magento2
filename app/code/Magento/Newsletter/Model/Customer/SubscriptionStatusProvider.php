<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Newsletter\Model\Customer;

use Magento\Customer\Block\Account\Newsletter\SubscriptionStatusProviderInterface;
use Magento\Newsletter\Model\SubscriberFactory;

class SubscriptionStatusProvider implements SubscriptionStatusProviderInterface
{
    public function __construct(
        private readonly SubscriberFactory $subscriberFactory
    ) {
    }

    public function isSubscribed(int $customerId, int $websiteId): bool
    {
        return (bool)$this->subscriberFactory->create()
            ->loadByCustomer($customerId, $websiteId)
            ->isSubscribed();
    }
}
