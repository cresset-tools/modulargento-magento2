<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Customer\Block\Form;

class NullNewsletterConfig implements NewsletterConfigInterface
{
    public function isActive(): bool
    {
        return false;
    }
}
