<?php
/**
 * Google Ads SDK client factory.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Google\Ads\GoogleAds\Lib\OAuth2TokenBuilder;
use Google\Ads\GoogleAds\Lib\V24\GoogleAdsClientBuilder;
use Rajled\AiAdsOs\Integration\GoogleAds\CredentialsManager;
use Rajled\AiAdsOs\Integration\GoogleAds\GoogleAdsConfiguration;
use Throwable;

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

        return $this->createClient($this->getLoginCustomerId());
    }

    public function createWithoutLoginCustomerId(): ?GoogleAdsSdkClient
    {
        if (! $this->canCreate()) {
            return null;
        }

        return $this->createClient(null);
    }

    public function createForLoginCustomerId(string $loginCustomerId): ?GoogleAdsSdkClient
    {
        if (! $this->canCreate()) {
            return null;
        }

        return $this->createClient($this->validateLoginCustomerId($loginCustomerId));
    }

    private function createClient(?int $loginCustomerId): GoogleAdsSdkClient
    {
        try {
            $oAuth2Credential = (new OAuth2TokenBuilder())
                ->withClientId(
                    $this->credentialsManager->getCredential(GoogleAdsConfiguration::CLIENT_ID)
                )
                ->withClientSecret(
                    $this->credentialsManager->getCredential(GoogleAdsConfiguration::CLIENT_SECRET)
                )
                ->withRefreshToken(
                    $this->credentialsManager->getCredential(GoogleAdsConfiguration::REFRESH_TOKEN)
                )
                ->build();

            $clientBuilder = (new GoogleAdsClientBuilder())
                ->withDeveloperToken(
                    $this->credentialsManager->getCredential(GoogleAdsConfiguration::DEVELOPER_TOKEN)
                )
                ->withOAuth2Credential($oAuth2Credential);

            if (null !== $loginCustomerId) {
                $clientBuilder->withLoginCustomerId($loginCustomerId);
            }

            return new GoogleAdsSdkClient(
                $this->credentialsManager,
                $clientBuilder->build()
            );
        } catch (Throwable $exception) {
            throw new GoogleAdsSdkException(
                'Unable to initialize the Google Ads PHP SDK client from configured credentials.',
                0,
                $exception,
                GoogleAdsSdkFailureClassifier::classify(
                    $exception,
                    GoogleAdsSdkException::STAGE_CLIENT_INITIALIZATION,
                    GoogleAdsSdkException::CATEGORY_CLIENT_INITIALIZATION
                )
            );
        }
    }

    private function getLoginCustomerId(): ?int
    {
        $loginCustomerId = $this->credentialsManager->getCredential(
            GoogleAdsConfiguration::LOGIN_CUSTOMER_ID
        );

        if ('' === $loginCustomerId) {
            return null;
        }

        return $this->validateLoginCustomerId($loginCustomerId);
    }

    private function validateLoginCustomerId(string $loginCustomerId): int
    {
        $validatedLoginCustomerId = filter_var(
            $loginCustomerId,
            FILTER_VALIDATE_INT,
            array('options' => array('min_range' => 1))
        );

        if (false === $validatedLoginCustomerId) {
            throw new GoogleAdsSdkException(
                'Google Ads login customer ID must be a positive integer.',
                0,
                null,
                array(
                    'sdk_failure_stage'    => GoogleAdsSdkException::STAGE_CLIENT_INITIALIZATION,
                    'sdk_failure_category' => GoogleAdsSdkException::CATEGORY_CLIENT_INITIALIZATION,
                )
            );
        }

        return $validatedLoginCustomerId;
    }
}
