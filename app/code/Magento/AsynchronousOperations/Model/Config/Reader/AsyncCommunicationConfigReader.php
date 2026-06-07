<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\AsynchronousOperations\Model\Config\Reader;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Config\ReaderInterface;

/**
 * Communication-config reader that contributes the async Web API topics only
 * when Magento_WebapiAsync is installed.
 *
 * Registered into the primary Communication CompositeReader (app/etc/di.xml) in
 * place of WebapiAsync's own reader, so the registration never dangles when the
 * Web API layer is removed: the WebapiAsync class reference is a plain string,
 * resolved lazily and guarded by class_exists, yielding an empty contribution
 * when the module is absent.
 */
class AsyncCommunicationConfigReader implements ReaderInterface
{
    /**
     * @var string
     */
    private $readerClass = \Magento\WebapiAsync\Code\Generator\Config\RemoteServiceReader\Communication::class;

    /**
     * @inheritdoc
     */
    public function read($scope = null)
    {
        if (!class_exists($this->readerClass)) {
            return [];
        }

        return ObjectManager::getInstance()->get($this->readerClass)->read($scope);
    }
}
