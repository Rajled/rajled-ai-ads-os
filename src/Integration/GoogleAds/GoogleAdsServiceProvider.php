<?php
/**
 * Google Ads service provider.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Core\ServiceContainer;
use Rajled\AiAdsOs\Core\ServiceProviderInterface;
use Rajled\AiAdsOs\Integration\IntegrationRegistry;

/**
 * Registers Google Ads integration layer services.
 */
final class GoogleAdsServiceProvider implements ServiceProviderInterface
{
    public function register(ServiceContainer $container): void
    {
        $container->singleton(
            GoogleAdsConfiguration::class,
            static function (ServiceContainer $container): GoogleAdsConfiguration {
                return GoogleAdsConfiguration::fromConfigurationManager(
                    $container->get(ConfigurationManager::class)
                );
            }
        );

        $container->singleton(
            CredentialsManager::class,
            static function (ServiceContainer $container): CredentialsManager {
                return new CredentialsManager(
                    $container->get(GoogleAdsConfiguration::class)
                );
            }
        );

        $container->singleton(
            ClientFactory::class,
            static function (ServiceContainer $container): ClientFactory {
                return new ClientFactory(
                    $container->get(CredentialsManager::class)
                );
            }
        );

        $container->singleton(
            ConnectionManager::class,
            static function (ServiceContainer $container): ConnectionManager {
                return new ConnectionManager(
                    $container->get(CredentialsManager::class),
                    $container->get(ClientFactory::class)
                );
            }
        );

        $container->singleton(
            ConnectionInterface::class,
            static function (ServiceContainer $container): ConnectionInterface {
                return $container->get(ConnectionManager::class);
            }
        );

        if ($container->has(IntegrationRegistry::class)) {
            $container->get(IntegrationRegistry::class)->register(
                $container->get(ConnectionInterface::class)
            );
        }
    }
}
