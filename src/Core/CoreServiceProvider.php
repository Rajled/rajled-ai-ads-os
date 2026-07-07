<?php
/**
 * Core service provider.
 *
 * @package Rajled\AiAdsOs\Core
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Core;

use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Engine\EngineRegistry;
use Rajled\AiAdsOs\Health\HealthManager;
use Rajled\AiAdsOs\Integration\GoogleAds\ConnectionInterface;
use Rajled\AiAdsOs\Logging\Logger;

/**
 * Registers framework-level services.
 */
final class CoreServiceProvider implements ServiceProviderInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $configurationValues;

    /**
     * @param array<string, mixed> $configurationValues Initial configuration values.
     */
    public function __construct(array $configurationValues)
    {
        $this->configurationValues = $configurationValues;
    }

    public function register(ServiceContainer $container): void
    {
        $configurationValues = $this->configurationValues;

        $container->singleton(
            ConfigurationManager::class,
            static function () use ($configurationValues): ConfigurationManager {
                return new ConfigurationManager($configurationValues);
            }
        );

        $container->singleton(
            Logger::class,
            static function (): Logger {
                return new Logger();
            }
        );

        $container->singleton(
            EventDispatcher::class,
            static function (): EventDispatcher {
                return new EventDispatcher();
            }
        );

        $container->singleton(
            EngineRegistry::class,
            static function (): EngineRegistry {
                return new EngineRegistry();
            }
        );

        $container->singleton(
            HealthManager::class,
            static function (ServiceContainer $container): HealthManager {
                $googleAdsConnection = null;

                if ($container->has(ConnectionInterface::class)) {
                    $googleAdsConnection = $container->get(ConnectionInterface::class);
                }

                return new HealthManager(
                    $container->get(ConfigurationManager::class),
                    $container->get(EngineRegistry::class),
                    $googleAdsConnection
                );
            }
        );
    }
}
