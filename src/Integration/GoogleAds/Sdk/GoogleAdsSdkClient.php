<?php
/**
 * Google Ads SDK client adapter.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Rajled\AiAdsOs\Integration\GoogleAds\ClientInterface;
use Rajled\AiAdsOs\Integration\GoogleAds\CredentialsManager;

/**
 * Wraps future Google Ads PHP SDK client access behind ClientInterface.
 */
final class GoogleAdsSdkClient implements ClientInterface
{
    private CredentialsManager $credentialsManager;

    private ?object $nativeClient;

    public function __construct(CredentialsManager $credentialsManager, ?object $nativeClient = null)
    {
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

    public function getNativeClient(): object
    {
        if (null === $this->nativeClient) {
            throw new GoogleAdsSdkException('Google Ads PHP SDK client is not initialized.');
        }

        return $this->nativeClient;
    }
}
