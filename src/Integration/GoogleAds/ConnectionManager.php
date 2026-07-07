<?php
/**
 * Google Ads connection manager.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

/**
 * Manages Google Ads connection readiness for the integration layer.
 */
final class ConnectionManager implements ConnectionInterface
{
    private CredentialsManager $credentialsManager;

    private ClientFactory $clientFactory;

    public function __construct(CredentialsManager $credentialsManager, ClientFactory $clientFactory)
    {
        $this->credentialsManager = $credentialsManager;
        $this->clientFactory = $clientFactory;
    }

    public function getProviderName(): string
    {
        return 'google_ads';
    }

    public function getStatus(): string
    {
        if ($this->isConfigured()) {
            return self::STATUS_CONFIGURED;
        }

        return self::STATUS_NOT_CONFIGURED;
    }

    public function isConfigured(): bool
    {
        return $this->credentialsManager->hasRequiredCredentials();
    }

    /**
     * Return missing credential keys.
     *
     * @return array<int, string>
     */
    public function getMissingCredentialKeys(): array
    {
        return $this->credentialsManager->getMissingCredentialKeys();
    }

    /**
     * Return Google Ads integration health status.
     *
     * @return array{status: string, configured: bool, missing_credentials: array<int, string>}
     */
    public function getHealthStatus(): array
    {
        return array(
            'status'              => $this->getStatus(),
            'configured'          => $this->isConfigured(),
            'missing_credentials' => $this->getMissingCredentialKeys(),
        );
    }

    public function getClient(): ?ClientInterface
    {
        return $this->clientFactory->create();
    }
}
