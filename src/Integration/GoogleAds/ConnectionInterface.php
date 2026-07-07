<?php
/**
 * Google Ads connection contract.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

use Rajled\AiAdsOs\Integration\IntegrationProviderInterface;

/**
 * Reports Google Ads connection readiness.
 */
interface ConnectionInterface extends IntegrationProviderInterface
{
    public const STATUS_CONFIGURED = 'configured';

    public const STATUS_NOT_CONFIGURED = 'not_configured';

    /**
     * Return missing credential keys.
     *
     * @return array<int, string>
     */
    public function getMissingCredentialKeys(): array;

    public function getClient(): ?ClientInterface;
}
