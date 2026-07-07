<?php
/**
 * Integration provider registry.
 *
 * @package Rajled\AiAdsOs\Integration
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration;

use InvalidArgumentException;

/**
 * Stores registered integration providers by provider name.
 */
final class IntegrationRegistry
{
    /**
     * @var array<string, IntegrationProviderInterface>
     */
    private array $providers = array();

    public function register(IntegrationProviderInterface $provider): void
    {
        $providerName = $this->normalizeProviderName($provider->getProviderName());

        $this->providers[$providerName] = $provider;
    }

    public function get(string $providerName): ?IntegrationProviderInterface
    {
        $normalizedProviderName = $this->normalizeProviderName($providerName);

        return $this->providers[$normalizedProviderName] ?? null;
    }

    /**
     * Return all registered integration providers.
     *
     * @return array<string, IntegrationProviderInterface>
     */
    public function all(): array
    {
        return $this->providers;
    }

    /**
     * Return health statuses for all registered providers.
     *
     * @return array<string, array<string, mixed>>
     */
    public function getHealthStatuses(): array
    {
        $statuses = array();

        foreach ($this->providers as $providerName => $provider) {
            $statuses[$providerName] = $provider->getHealthStatus();
        }

        return $statuses;
    }

    public function count(): int
    {
        return count($this->providers);
    }

    private function normalizeProviderName(string $providerName): string
    {
        $normalizedProviderName = trim($providerName);

        if ('' === $normalizedProviderName) {
            throw new InvalidArgumentException('Integration provider name cannot be empty.');
        }

        return $normalizedProviderName;
    }
}
