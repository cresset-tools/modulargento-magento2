<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\Wishlist\Model\Sales;

use Magento\Sales\Block\Adminhtml\Order\Create\Items\CustomerWishlistsProviderInterface;
use Magento\Wishlist\Model\WishlistFactory;

class CustomerWishlistsProvider implements CustomerWishlistsProviderInterface
{
    /**
     * @var WishlistFactory
     */
    private $wishlistFactory;

    public function __construct(WishlistFactory $wishlistFactory)
    {
        $this->wishlistFactory = $wishlistFactory;
    }

    public function get($customerId): iterable
    {
        if (!$customerId) {
            return [];
        }
        return $this->wishlistFactory->create()->getCollection()->filterByCustomerId($customerId);
    }
}
