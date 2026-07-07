<?php
/**
 * Health manager.
 *
 * @package Rajled\AiAdsOs\Health
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Health;

use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Engine\EngineRegistry;
use Rajled\AiAdsOs\Integration\GoogleAds\ConnectionInterface;

/**
 * Provides the current system health status.
 */
final class HealthManager
{
    private ConfigurationManager $configuration;

    private EngineRegistry $engineRegistry;

    private ?ConnectionInterface $googleAdsConnection;

    public function __construct(
        ConfigurationManager $configuration,
        EngineRegistry $engineRegistry,
        ?ConnectionInterface $googleAdsConnection = null
    ) {
        $this->configuration = $configuration;
        $this->engineRegistry = $engineRegistry;
        $this->googleAdsConnection = $googleAdsConnection;
    }

    /**
     * Return basic system status.
     *
     * @return array{
     *     status: string,
     *     version: string,
     *     engines: int,
     *     google_ads: array{
     *         status: string,
     *         configured: bool,
     *         missing_credentials: array<int, string>
     *     }
     * }
     */
    public function getStatus(): array
    {
        return array(
            'status'     => 'ok',
            'version'    => $this->configuration->getVersion(),
            'engines'    => $this->engineRegistry->count(),
            'google_ads' => $this->getGoogleAdsStatus(),
        );
    }

    /**
     * Return Google Ads integration status.
     *
     * @return array{status: string, configured: bool, missing_credentials: array<int, string>}
     */
    private function getGoogleAdsStatus(): array
    {
        if (null === $this->googleAdsConnection) {
            return array(
                'status'              => 'unavailable',
                'configured'          => false,
                'missing_credentials' => array(),
            );
        }

        return array(
            'status'              => $this->googleAdsConnection->getStatus(),
            'configured'          => $this->googleAdsConnection->isConfigured(),
            'missing_credentials' => $this->googleAdsConnection->getMissingCredentialKeys(),
        );
    }
}
