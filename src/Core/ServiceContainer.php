<?php
/**
 * Service container.
 *
 * @package Rajled\AiAdsOs\Core
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Core;

use InvalidArgumentException;

/**
 * Registers and resolves application services.
 */
final class ServiceContainer
{
    /**
     * @var array<string, array{factory: callable, shared: bool}>
     */
    private array $definitions = array();

    /**
     * @var array<string, mixed>
     */
    private array $instances = array();

    public function register(string $key, callable $factory): void
    {
        $normalizedKey = $this->normalizeKey($key);

        $this->definitions[$normalizedKey] = array(
            'factory' => $factory,
            'shared'  => false,
        );

        unset($this->instances[$normalizedKey]);
    }

    public function singleton(string $key, callable $factory): void
    {
        $normalizedKey = $this->normalizeKey($key);

        $this->definitions[$normalizedKey] = array(
            'factory' => $factory,
            'shared'  => true,
        );

        unset($this->instances[$normalizedKey]);
    }

    public function has(string $key): bool
    {
        $normalizedKey = trim($key);

        return isset($this->definitions[$normalizedKey]) || array_key_exists($normalizedKey, $this->instances);
    }

    public function get(string $key): mixed
    {
        $normalizedKey = $this->normalizeKey($key);

        if (array_key_exists($normalizedKey, $this->instances)) {
            return $this->instances[$normalizedKey];
        }

        if (! isset($this->definitions[$normalizedKey])) {
            throw new InvalidArgumentException(
                sprintf('Service "%s" is not registered.', $normalizedKey)
            );
        }

        $definition = $this->definitions[$normalizedKey];
        $service = $definition['factory']($this);

        if ($definition['shared']) {
            $this->instances[$normalizedKey] = $service;
        }

        return $service;
    }

    private function normalizeKey(string $key): string
    {
        $normalizedKey = trim($key);

        if ('' === $normalizedKey) {
            throw new InvalidArgumentException('Service key cannot be empty.');
        }

        return $normalizedKey;
    }
}
