<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Customer\Block\Account\Newsletter;

interface SubscriptionStatusProviderInterface
{
    public function isSubscribed(int $customerId, int $websiteId): bool;
}
