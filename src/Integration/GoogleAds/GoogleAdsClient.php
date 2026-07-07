<?php
/**
 * Google Ads client adapter placeholder.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

/**
 * Represents a configured Google Ads client without performing API calls.
 */
final class GoogleAdsClient implements ClientInterface
{
    private CredentialsManager $credentialsManager;

    public function __construct(CredentialsManager $credentialsManager)
    {
        $this->credentialsManager = $credentialsManager;
    }

    public function getProviderName(): string
    {
        return 'google_ads';
    }

    public function isConfigured(): bool
    {
        return $this->credentialsManager->hasRequiredCredentials();
    }
}
