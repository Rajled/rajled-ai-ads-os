<?php
/**
 * Provider registry.
 *
 * @package Rajled\AiAdsOs\Core
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Core;

use LogicException;

/**
 * Maintains and boots service providers.
 */
final class ProviderRegistry
{
    /**
     * @var array<int, ServiceProviderInterface>
     */
    private array $providers = array();

    private bool $booted = false;

    public function register(ServiceProviderInterface $provider): void
    {
        if ($this->booted) {
            throw new LogicException('Providers cannot be registered after the registry has booted.');
        }

        $this->providers[] = $provider;
    }

    public function boot(ServiceContainer $container): void
    {
        if ($this->booted) {
            return;
        }

        foreach ($this->providers as $provider) {
            $provider->register($container);
        }

        $this->booted = true;
    }

    /**
     * Return registered providers.
     *
     * @return array<int, ServiceProviderInterface>
     */
    public function all(): array
    {
        return $this->providers;
    }

    public function count(): int
    {
        return count($this->providers);
    }
}
