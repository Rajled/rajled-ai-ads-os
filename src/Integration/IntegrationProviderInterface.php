<?php
/**
 * Integration provider contract.
 *
 * @package Rajled\AiAdsOs\Integration
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration;

/**
 * Represents an external integration provider registered in the platform.
 */
interface IntegrationProviderInterface
{
    public function getProviderName(): string;

    public function getStatus(): string;

    public function isConfigured(): bool;

    /**
     * Return provider health status.
     *
     * @return array<string, mixed>
     */
    public function getHealthStatus(): array;
}
