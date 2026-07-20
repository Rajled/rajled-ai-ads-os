<?php
/**
 * Accessible Google Ads account result.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Account;

/**
 * Carries the provider resource name and customer identifier discovered by Google Ads.
 */
final class AccessibleGoogleAdsAccount
{
    private string $resourceName;

    private string $customerId;

    public function __construct(string $resourceName, string $customerId)
    {
        $this->resourceName = $resourceName;
        $this->customerId = $customerId;
    }

    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }
}
