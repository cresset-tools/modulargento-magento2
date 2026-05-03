<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Sales\Block\Adminhtml\Order\Create\Items;

class NullCustomerWishlistsProvider implements CustomerWishlistsProviderInterface
{
    public function get($customerId): iterable
    {
        return [];
    }
}
