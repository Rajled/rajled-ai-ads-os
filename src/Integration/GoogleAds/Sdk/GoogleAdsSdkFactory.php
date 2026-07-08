<?php
/**
 * Google Ads SDK client factory.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Rajled\AiAdsOs\Integration\GoogleAds\CredentialsManager;

/**
 * Creates Google Ads SDK adapters without performing live API calls.
 */
final class GoogleAdsSdkFactory
{
    private CredentialsManager $credentialsManager;

    public function __construct(CredentialsManager $credentialsManager)
    {
        $this->credentialsManager = $credentialsManager;
    }

    public function canCreate(): bool
    {
        return $this->credentialsManager->hasRequiredCredentials();
    }

    public function create(): ?GoogleAdsSdkClient
    {
        if (! $this->canCreate()) {
            return null;
        }

        return new GoogleAdsSdkClient($this->credentialsManager);
    }
}
