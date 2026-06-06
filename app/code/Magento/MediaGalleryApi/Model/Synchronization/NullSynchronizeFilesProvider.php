<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\MediaGalleryApi\Model\Synchronization;

/**
 * No-op default used when Magento_MediaGallerySynchronization is not installed.
 */
class NullSynchronizeFilesProvider implements SynchronizeFilesProviderInterface
{
    /**
     * @inheritDoc
     */
    public function execute(array $paths): void
    {
        // No synchronization available — nothing to do.
    }
}
