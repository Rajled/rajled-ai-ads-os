<?php
/**
 * Configuration manager.
 *
 * @package Rajled\AiAdsOs\Config
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Config;

/**
 * Stores plugin configuration values.
 */
final class ConfigurationManager
{
    public const PLUGIN_NAME = 'plugin_name';

    public const VERSION = 'version';

    public const REST_NAMESPACE = 'rest_namespace';

    /**
     * @var array<string, mixed>
     */
    private array $values;

    /**
     * @param array<string, mixed> $values Initial configuration values.
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * Return a configuration value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->values[$key] ?? $default;
    }

    public function getPluginName(): string
    {
        return (string) $this->get(self::PLUGIN_NAME, 'RajLED AI Ads OS');
    }

    public function getVersion(): string
    {
        return (string) $this->get(self::VERSION, '0.0.0');
    }

    public function getRestNamespace(): string
    {
        return (string) $this->get(self::REST_NAMESPACE, 'rajled-ai-ads/v1');
    }

    /**
     * Return all configuration values.
     *
     * @return array<string, mixed>
     */
    public function all(): array
    {
        return $this->values;
    }
}
