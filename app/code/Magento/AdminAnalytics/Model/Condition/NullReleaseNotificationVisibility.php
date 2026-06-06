<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\AdminAnalytics\Model\Condition;

/**
 * Default no-op visibility used when Magento_ReleaseNotification is not installed:
 * the release-notification modal is never shown.
 */
class NullReleaseNotificationVisibility implements ReleaseNotificationVisibilityInterface
{
    private const NAME = 'can_view_null_release_notification';

    /**
     * @inheritDoc
     */
    public function isVisible(array $arguments)
    {
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::NAME;
    }
}
