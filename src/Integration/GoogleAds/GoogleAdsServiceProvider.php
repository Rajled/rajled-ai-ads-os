<?php
/**
 * Google Ads service provider.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds;

use Rajled\AiAdsOs\Application\Account\AccountCatalogInterface;
use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Core\ServiceContainer;
use Rajled\AiAdsOs\Core\ServiceProviderInterface;
use Rajled\AiAdsOs\Integration\GoogleAds\Account\GoogleAdsAccountCatalog;
use Rajled\AiAdsOs\Integration\GoogleAds\Mapping\GoogleAdsAccountMapper;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsAccountDiscovery;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsAccountDiscoveryFailurePolicy;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsAccountDetailsReader;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsConnectivityChecker;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsConnectivityHealthCheck;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsSdkFactory;
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
            GoogleAdsSdkFactory::class,
            static function (ServiceContainer $container): GoogleAdsSdkFactory {
                return new GoogleAdsSdkFactory(
                    $container->get(CredentialsManager::class)
                );
            }
        );

        $container->singleton(
            GoogleAdsAccountDiscovery::class,
            static function (ServiceContainer $container): GoogleAdsAccountDiscovery {
                return new GoogleAdsAccountDiscovery(
                    $container->get(GoogleAdsSdkFactory::class)
                );
            }
        );

        $container->singleton(
            GoogleAdsAccountDiscoveryFailurePolicy::class,
            static function (): GoogleAdsAccountDiscoveryFailurePolicy {
                return new GoogleAdsAccountDiscoveryFailurePolicy();
            }
        );

        $container->singleton(
            GoogleAdsAccountDetailsReader::class,
            static function (ServiceContainer $container): GoogleAdsAccountDetailsReader {
                return new GoogleAdsAccountDetailsReader(
                    $container->get(GoogleAdsAccountDiscovery::class),
                    $container->get(GoogleAdsSdkFactory::class),
                    $container->get(GoogleAdsAccountDiscoveryFailurePolicy::class)
                );
            }
        );

        $container->singleton(
            GoogleAdsAccountMapper::class,
            static function (): GoogleAdsAccountMapper {
                return new GoogleAdsAccountMapper();
            }
        );

        $container->singleton(
            GoogleAdsAccountCatalog::class,
            static function (ServiceContainer $container): GoogleAdsAccountCatalog {
                return new GoogleAdsAccountCatalog(
                    $container->get(GoogleAdsAccountDetailsReader::class),
                    $container->get(GoogleAdsAccountMapper::class),
                    $container->get(GoogleAdsConfiguration::class)
                );
            }
        );

        $container->singleton(
            AccountCatalogInterface::class,
            static function (ServiceContainer $container): AccountCatalogInterface {
                return $container->get(GoogleAdsAccountCatalog::class);
            }
        );

        $container->register(
            GoogleAdsConnectivityChecker::class,
            static function (ServiceContainer $container): GoogleAdsConnectivityChecker {
                return new GoogleAdsConnectivityChecker(
                    $container->get(GoogleAdsAccountDiscovery::class)
                );
            }
        );

        $container->singleton(
            GoogleAdsConnectivityHealthCheck::class,
            static function (ServiceContainer $container): GoogleAdsConnectivityHealthCheck {
                return new GoogleAdsConnectivityHealthCheck(
                    $container->get(CredentialsManager::class),
                    static function () use ($container): GoogleAdsConnectivityChecker {
                        return $container->get(GoogleAdsConnectivityChecker::class);
                    }
                );
            }
        );

        $container->singleton(
            ClientFactory::class,
            static function (ServiceContainer $container): ClientFactory {
                return new ClientFactory(
                    $container->get(GoogleAdsSdkFactory::class)
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
