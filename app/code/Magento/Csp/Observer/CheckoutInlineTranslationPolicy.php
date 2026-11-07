<?php
/**
 * Copyright 2024 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\Csp\Observer;

use Magento\Csp\Model\Collector\DynamicCollector;
use Magento\Csp\Model\Policy\FetchPolicy;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Translate\InlineInterface;

/**
 * Allows inline event handlers on the checkout page while inline translation is on.
 *
 * Lives in Magento_Csp (it used to be Magento\Checkout\Observer\CspPolicyObserver) so
 * Magento_Checkout doesn't depend on Magento_Csp; it only listens for the checkout
 * page's predispatch event, which fires only when Magento_Checkout is installed.
 */
class CheckoutInlineTranslationPolicy implements ObserverInterface
{
    /**
     * @var InlineInterface
     */
    private InlineInterface $inlineTranslate;

    /**
     * @var DynamicCollector
     */
    private DynamicCollector $dynamicCollector;

    /**
     * @param InlineInterface $inlineTranslate
     * @param DynamicCollector $dynamicCollector
     */
    public function __construct(InlineInterface $inlineTranslate, DynamicCollector $dynamicCollector)
    {
        $this->inlineTranslate = $inlineTranslate;
        $this->dynamicCollector = $dynamicCollector;
    }

    /**
     * Override CSP policy for checkout page wit inline translation
     *
     * @param Observer $observer
     * @return void
     *
     * @throws \Exception
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(Observer $observer): void
    {
        if ($this->inlineTranslate->isAllowed()) {
            $policy = new FetchPolicy(
                'script-src',
                false,
                [],
                [],
                true,
                true,
                false,
                [],
                []
            );

            $this->dynamicCollector->add($policy);
        }
    }
}
