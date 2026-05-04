<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Customer\Controller\Adminhtml\Newsletter;

class NullAdminSubscriptionToggle implements AdminSubscriptionToggleInterface
{
    public function apply(int $customerId, int $storeId, bool $subscribed): void
    {
    }
}
