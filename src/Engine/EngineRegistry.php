<?php
/**
 * Engine registry.
 *
 * @package Rajled\AiAdsOs\Engine
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Engine;

use InvalidArgumentException;

/**
 * Stores registered engine instances by key.
 */
final class EngineRegistry
{
    /**
     * @var array<string, EngineInterface>
     */
    private array $engines = array();

    public function register(string $key, EngineInterface $engine): void
    {
        $normalizedKey = trim($key);

        if ('' === $normalizedKey) {
            throw new InvalidArgumentException('Engine key cannot be empty.');
        }

        $this->engines[$normalizedKey] = $engine;
    }

    public function get(string $key): ?EngineInterface
    {
        return $this->engines[$key] ?? null;
    }

    /**
     * Return all registered engines.
     *
     * @return array<string, EngineInterface>
     */
    public function all(): array
    {
        return $this->engines;
    }

    public function count(): int
    {
        return count($this->engines);
    }
}
