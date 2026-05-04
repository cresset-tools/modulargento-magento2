<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Newsletter\Model\Customer;

use Magento\Customer\Block\Form\NewsletterConfigInterface;
use Magento\Newsletter\Model\Config;
use Magento\Store\Model\ScopeInterface;

class BlockNewsletterConfig implements NewsletterConfigInterface
{
    public function __construct(
        private readonly Config $config
    ) {
    }

    public function isActive(): bool
    {
        return (bool)$this->config->isActive(ScopeInterface::SCOPE_STORE);
    }
}
