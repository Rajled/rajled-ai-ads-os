<?php
/**
 * Google Ads credentials manager.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

/**
 * Validates presence of Google Ads credentials without exposing secret values.
 */
final class CredentialsManager
{
    private GoogleAdsConfiguration $configuration;

    public function __construct(GoogleAdsConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function hasRequiredCredentials(): bool
    {
        return $this->configuration->hasRequiredValues();
    }

    /**
     * Return missing required credential keys.
     *
     * @return array<int, string>
     */
    public function getMissingCredentialKeys(): array
    {
        return $this->configuration->getMissingRequiredFields();
    }

    public function getCredential(string $key): string
    {
        return $this->configuration->getValue($key);
    }
}
