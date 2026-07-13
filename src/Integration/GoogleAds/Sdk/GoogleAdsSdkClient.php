<?php
/**
 * Google Ads SDK client adapter.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Google\Ads\GoogleAds\Lib\V24\GoogleAdsClient;
use Rajled\AiAdsOs\Integration\GoogleAds\ClientInterface;
use Rajled\AiAdsOs\Integration\GoogleAds\CredentialsManager;

/**
 * Wraps Google Ads PHP SDK client access behind ClientInterface.
 */
final class GoogleAdsSdkClient implements ClientInterface
{
    private CredentialsManager $credentialsManager;

    private ?GoogleAdsClient $nativeClient;

    public function __construct(
        CredentialsManager $credentialsManager,
        ?GoogleAdsClient $nativeClient = null
    ) {
        $this->credentialsManager = $credentialsManager;
        $this->nativeClient = $nativeClient;
    }

    public function getProviderName(): string
    {
        return 'google_ads';
    }

    public function isConfigured(): bool
    {
        return $this->credentialsManager->hasRequiredCredentials();
    }

    public function hasNativeClient(): bool
    {
        return null !== $this->nativeClient;
    }

    public function getNativeClient(): GoogleAdsClient
    {
        if (null === $this->nativeClient) {
            throw new GoogleAdsSdkException('Google Ads PHP SDK client is not initialized.');
        }

        return $this->nativeClient;
    }
}
