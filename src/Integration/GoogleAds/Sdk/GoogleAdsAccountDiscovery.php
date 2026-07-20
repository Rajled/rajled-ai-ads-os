<?php
/**
 * Google Ads account discovery service.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Google\Ads\GoogleAds\V24\Services\ListAccessibleCustomersRequest;
use Rajled\AiAdsOs\Integration\GoogleAds\Account\AccessibleGoogleAdsAccount;
use Rajled\AiAdsOs\Integration\GoogleAds\Account\InvalidGoogleAdsAccountResourceNameException;
use Throwable;

/**
 * Discovers accounts available to the configured Google Ads credentials.
 */
final class GoogleAdsAccountDiscovery
{
    private GoogleAdsSdkFactory $clientFactory;

    public function __construct(GoogleAdsSdkFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

    /**
     * Return directly accessible Google Ads accounts.
     *
     * @return list<AccessibleGoogleAdsAccount>
     */
    public function discover(): array
    {
        $client = $this->clientFactory->create();

        if (null === $client || ! $client->hasNativeClient()) {
            throw new GoogleAdsSdkException(
                'Google Ads PHP SDK client is not initialized.'
            );
        }

        try {
            $response = $client
                ->getNativeClient()
                ->getCustomerServiceClient()
                ->listAccessibleCustomers(new ListAccessibleCustomersRequest());
            $resourceNames = $response->getResourceNames();
        } catch (Throwable $exception) {
            throw new GoogleAdsSdkException(
                'Unable to discover accessible Google Ads accounts.',
                0,
                $exception
            );
        }

        $accounts = array();

        foreach ($resourceNames as $resourceName) {
            $accounts[] = $this->createAccount($resourceName);
        }

        return $accounts;
    }

    private function createAccount(mixed $resourceName): AccessibleGoogleAdsAccount
    {
        if (! is_string($resourceName)) {
            throw new InvalidGoogleAdsAccountResourceNameException(
                'Google Ads returned an invalid account resource name.'
            );
        }

        $resourceName = trim($resourceName);
        $matches = array();

        if (1 !== preg_match('/\Acustomers\/([0-9]+)\z/', $resourceName, $matches)) {
            throw new InvalidGoogleAdsAccountResourceNameException(
                'Google Ads returned an invalid account resource name.'
            );
        }

        $customerId = $matches[1];

        return new AccessibleGoogleAdsAccount($resourceName, $customerId);
    }
}
