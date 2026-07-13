<?php
/**
 * Google Ads SDK connectivity checker.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Google\Ads\GoogleAds\V24\Services\ListAccessibleCustomersRequest;
use Throwable;
use UnexpectedValueException;

/**
 * Verifies authenticated Google Ads API connectivity with a read-only operation.
 */
final class GoogleAdsConnectivityChecker
{
    private GoogleAdsSdkClient $client;

    public function __construct(GoogleAdsSdkClient $client)
    {
        $this->client = $client;
    }

    /**
     * Return directly accessible customer resource names.
     *
     * @return list<string>
     */
    public function check(): array
    {
        try {
            $response = $this->client
                ->getNativeClient()
                ->getCustomerServiceClient()
                ->listAccessibleCustomers(new ListAccessibleCustomersRequest());

            return $this->normalizeResourceNames($response->getResourceNames());
        } catch (Throwable $exception) {
            throw new GoogleAdsSdkException(
                'Unable to verify Google Ads API connectivity.',
                0,
                $exception
            );
        }
    }

    /**
     * @param iterable<mixed> $resourceNames Customer resource names returned by the SDK.
     *
     * @return list<string>
     */
    private function normalizeResourceNames(iterable $resourceNames): array
    {
        $normalizedResourceNames = array();

        foreach ($resourceNames as $resourceName) {
            if (! is_string($resourceName) || '' === trim($resourceName)) {
                throw new UnexpectedValueException(
                    'Google Ads API returned an invalid customer resource name.'
                );
            }

            $normalizedResourceNames[] = trim($resourceName);
        }

        return $normalizedResourceNames;
    }
}
