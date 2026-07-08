<?php
/**
 * Google Ads client factory.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsSdkFactory;

/**
 * Creates Google Ads client adapters when credentials are available.
 */
final class ClientFactory
{
    private GoogleAdsSdkFactory $sdkFactory;

    public function __construct(CredentialsManager|GoogleAdsSdkFactory $clientFactory)
    {
        if ($clientFactory instanceof CredentialsManager) {
            $clientFactory = new GoogleAdsSdkFactory($clientFactory);
        }

        $this->sdkFactory = $clientFactory;
    }

    public function canCreate(): bool
    {
        return $this->sdkFactory->canCreate();
    }

    public function create(): ?ClientInterface
    {
        return $this->sdkFactory->create();
    }
}
