<?php
/**
 * Google Ads client contract.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

/**
 * Represents a Google Ads API client adapter.
 */
interface ClientInterface
{
    public function getProviderName(): string;

    public function isConfigured(): bool;
}
