<?php
/**
 * Google Ads SDK connectivity checker.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

/**
 * Verifies authenticated Google Ads API connectivity with a read-only operation.
 */
final class GoogleAdsConnectivityChecker
{
    private GoogleAdsAccountDiscovery $accountDiscovery;

    public function __construct(GoogleAdsAccountDiscovery $accountDiscovery)
    {
        $this->accountDiscovery = $accountDiscovery;
    }

    /**
     * Return directly accessible customer resource names.
     *
     * @return list<string>
     */
    public function check(): array
    {
        $resourceNames = array();

        foreach ($this->accountDiscovery->discover() as $account) {
            $resourceNames[] = $account->getResourceName();
        }

        return $resourceNames;
    }
}
