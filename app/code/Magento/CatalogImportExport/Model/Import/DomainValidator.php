<?php
/**
 * Copyright 2025 Adobe
 * All Rights Reserved.
 */
declare(strict_types=1);

namespace Magento\CatalogImportExport\Model\Import;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Validator\Ip as IpValidator;
use Laminas\Uri\Uri as UriHandler;

/**
 * Validates that a media-import URL's host is on the allowed-domains whitelist.
 *
 * Self-contained in Magento_CatalogImportExport so URL media import (used for all
 * product types) carries no hard dependency on Magento_Downloadable. The whitelist
 * is the same deployment-config key historically owned by Downloadable
 * ('downloadable_domains'), so existing env.php whitelists keep working and the
 * value stays consistent with Magento_Downloadable's own validator when present.
 */
class DomainValidator
{
    /**
     * Deployment-config path to the allowed import-domains whitelist.
     */
    public const PARAM_DOWNLOADABLE_DOMAINS = 'downloadable_domains';

    /**
     * @param DeploymentConfig $deploymentConfig
     * @param IpValidator $ipValidator
     * @param UriHandler $uriHandler
     */
    public function __construct(
        private readonly DeploymentConfig $deploymentConfig,
        private readonly IpValidator $ipValidator,
        private readonly UriHandler $uriHandler
    ) {
    }

    /**
     * Validate url input — assert the parsed host is contained within the whitelist.
     *
     * @param string $value
     * @return bool
     */
    public function isValid($value): bool
    {
        $host = $this->getHost($value);

        $isIpAddress = $this->ipValidator->isValid($host);

        return !$isIpAddress && in_array($host, $this->getDomains(), true);
    }

    /**
     * The lower-cased allowed-domains whitelist from deployment config.
     *
     * @return array
     */
    private function getDomains(): array
    {
        return array_map('strtolower', $this->deploymentConfig->get(self::PARAM_DOWNLOADABLE_DOMAINS) ?? []);
    }

    /**
     * Extract host from url (ipv6 hosts are brace-delimited and unwrapped here).
     *
     * @param string $url
     * @return string
     */
    private function getHost($url): string
    {
        $host = $this->uriHandler->parse($url)->getHost();

        if ($host === null) {
            return '';
        }

        return trim($host, '[] ');
    }
}
