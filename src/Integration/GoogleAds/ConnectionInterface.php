<?php
/**
 * Google Ads connection contract.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

/**
 * Reports Google Ads connection readiness.
 */
interface ConnectionInterface
{
    public const STATUS_CONFIGURED = 'configured';

    public const STATUS_NOT_CONFIGURED = 'not_configured';

    public function getProviderName(): string;

    public function getStatus(): string;

    public function isConfigured(): bool;

    /**
     * Return missing credential keys.
     *
     * @return array<int, string>
     */
    public function getMissingCredentialKeys(): array;

    public function getClient(): ?ClientInterface;
}
