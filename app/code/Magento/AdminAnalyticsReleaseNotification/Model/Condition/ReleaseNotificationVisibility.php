<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AdminAnalyticsReleaseNotification\Model\Condition;

use Magento\AdminAnalytics\Model\Condition\ReleaseNotificationVisibilityInterface;
use Magento\ReleaseNotification\Model\Condition\CanViewNotification;

/**
 * Binds Magento_ReleaseNotification's CanViewNotification to the AdminAnalytics
 * release-notes visibility seam.
 *
 * CanViewNotification itself only implements the framework
 * VisibilityConditionInterface so Magento_ReleaseNotification stays installable
 * without Magento_AdminAnalytics. This bridge — present only when both modules
 * are — adds the AdminAnalytics interface marker the modal's ViewModel expects.
 * When AdminAnalytics is absent the bridge is too, and AdminAnalytics' own
 * NullReleaseNotificationVisibility default applies.
 */
class ReleaseNotificationVisibility extends CanViewNotification implements ReleaseNotificationVisibilityInterface
{
}
