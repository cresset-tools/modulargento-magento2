<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\MediaGalleryApi\Model\Synchronization;

/**
 * Optional "synchronize these media files into the gallery" seam, so media-gallery
 * integration modules don't hard-depend on Magento_MediaGallerySynchronization.
 * Magento_MediaGallerySynchronization binds the real implementation; the null
 * default (NullSynchronizeFilesProvider) is a no-op when synchronization is absent.
 */
interface SynchronizeFilesProviderInterface
{
    /**
     * Synchronize the given media files into the media gallery.
     *
     * @param string[] $paths
     * @return void
     */
    public function execute(array $paths): void;
}
