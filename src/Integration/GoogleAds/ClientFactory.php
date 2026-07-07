<?php
/**
 * Google Ads client factory.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

/**
 * Creates Google Ads client adapters when credentials are available.
 */
final class ClientFactory
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

    public function create(): ?ClientInterface
    {
        if (! $this->canCreate()) {
            return null;
        }

        return new GoogleAdsClient($this->credentialsManager);
    }
}
