<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\AdminAnalytics\Model\Condition;

use Magento\Framework\View\Layout\Condition\VisibilityConditionInterface;

/**
 * Visibility seam for the admin release-notification modal.
 *
 * Lets Magento_AdminAnalytics decide whether to show the release-notes popup
 * without a hard dependency on Magento_ReleaseNotification: the null default
 * reports "not visible" so the popup is simply hidden when that module is
 * absent, while Magento_ReleaseNotification binds its real CanViewNotification.
 */
interface ReleaseNotificationVisibilityInterface extends VisibilityConditionInterface
{
}
