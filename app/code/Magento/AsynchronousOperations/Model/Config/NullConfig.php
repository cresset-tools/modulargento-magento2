<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousOperations\Model\Config;

use Magento\AsynchronousOperations\Model\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Default async-config used when Magento_WebapiAsync is absent.
 *
 * WebapiAsync derives the async service/topic map from the REST webapi.xml
 * declarations; without it there are no asynchronous Web API services, so this
 * reports an empty set. The bulk-operation framework keeps working — it simply
 * has no async REST endpoints. WebapiAsync overrides this with the real config.
 */
class NullConfig implements ConfigInterface
{
    /**
     * @inheritdoc
     */
    public function getServices()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function getTopicName($routeUrl, $httpMethod)
    {
        throw new LocalizedException(
            __('WebapiAsync config for "%routeUrl %httpMethod" does not exist.', [
                'routeUrl' => $routeUrl,
                'httpMethod' => $httpMethod,
            ])
        );
    }
}
