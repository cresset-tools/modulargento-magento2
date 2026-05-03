<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Sales\Block\Adminhtml\Order\Create\Items;

/**
 * Provides the wishlists belonging to the customer whose order is being
 * created in the admin "Create Order" flow.
 *
 * Sales ships a null implementation that returns []; Magento_Wishlist
 * binds a real implementation when present. This is the seam that lets
 * Magento_Wishlist be removed without breaking Magento_Sales DI.
 *
 * @api
 */
interface CustomerWishlistsProviderInterface
{
    /**
     * @param int|null $customerId
     * @return iterable
     */
    public function get($customerId): iterable;
}
