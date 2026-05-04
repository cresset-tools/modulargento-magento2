<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Newsletter\Model\CustomerGraphQl;

use Magento\CustomerGraphQl\Model\Customer\Newsletter\NewsletterAvailabilityInterface;
use Magento\Newsletter\Model\Config;
use Magento\Store\Model\ScopeInterface;

class NewsletterAvailability implements NewsletterAvailabilityInterface
{
    public function __construct(
        private readonly Config $config
    ) {
    }

    public function isAvailable(): bool
    {
        return (bool)$this->config->isActive(ScopeInterface::SCOPE_STORE);
    }
}
