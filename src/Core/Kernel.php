<?php
/**
 * Plugin kernel.
 *
 * @package Rajled\AiAdsOs\Core
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Core;

use Rajled\AiAdsOs\Application\Account\AccountCatalogInterface;
use Rajled\AiAdsOs\Application\Account\AccountSelectionService;
use Rajled\AiAdsOs\Application\Account\ActiveAccountStoreInterface;
use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Dashboard\DashboardPage;
use Rajled\AiAdsOs\Dashboard\GoogleAds\GoogleAdsAccountPanel;
use Rajled\AiAdsOs\Engine\EngineRegistry;
use Rajled\AiAdsOs\Health\HealthManager;
use Rajled\AiAdsOs\Integration\GoogleAds\GoogleAdsServiceProvider;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsConnectivityHealthCheck;
use Rajled\AiAdsOs\Infrastructure\WordPress\GoogleAds\GoogleAdsActiveAccountStore;
use Rajled\AiAdsOs\Logging\Logger;
use Rajled\AiAdsOs\Rest\RestController;

/**
 * Coordinates framework initialization for the plugin.
 */
final class Kernel
{
    private ServiceContainer $container;

    private ProviderRegistry $providerRegistry;

    private bool $booted = false;

    private function __construct(ServiceContainer $container, ProviderRegistry $providerRegistry)
    {
        $this->container = $container;
        $this->providerRegistry = $providerRegistry;
    }

    /**
     * Create a kernel with the default core services.
     *
     * @param array<string, mixed> $configurationValues Initial configuration values.
     */
    public static function create(array $configurationValues): self
    {
        $container = new ServiceContainer();
        $providerRegistry = new ProviderRegistry();
        $providerRegistry->register(new CoreServiceProvider($configurationValues));
        $providerRegistry->register(new GoogleAdsServiceProvider());
        $providerRegistry->boot($container);

        $kernel = new self($container, $providerRegistry);
        $kernel->registerWordPressAdapters();

        return $kernel;
    }

    /**
     * Register WordPress integrations.
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        $restController = $this->container->get(RestController::class);
        $dashboardPage = $this->container->get(DashboardPage::class);
        $configuration = $this->container->get(ConfigurationManager::class);
        $logger = $this->container->get(Logger::class);

        add_action('rest_api_init', array($restController, 'registerRoutes'));
        add_action('admin_menu', array($dashboardPage, 'registerMenu'));

        $logger->info(
            'Kernel booted.',
            array(
                'version'        => $this->getVersion(),
                'rest_namespace' => $configuration->getRestNamespace(),
            )
        );

        $this->booted = true;
    }

    public function getVersion(): string
    {
        return $this->getConfiguration()->getVersion();
    }

    public function getConfiguration(): ConfigurationManager
    {
        return $this->container->get(ConfigurationManager::class);
    }

    public function getEngineRegistry(): EngineRegistry
    {
        return $this->container->get(EngineRegistry::class);
    }

    public function getHealthManager(): HealthManager
    {
        return $this->container->get(HealthManager::class);
    }

    public function getContainer(): ServiceContainer
    {
        return $this->container;
    }

    public function getProviderRegistry(): ProviderRegistry
    {
        return $this->providerRegistry;
    }

    private function registerWordPressAdapters(): void
    {
        $this->container->singleton(
            ActiveAccountStoreInterface::class,
            static function (ServiceContainer $container): ActiveAccountStoreInterface {
                return new GoogleAdsActiveAccountStore(
                    $container->get(Logger::class)
                );
            }
        );

        $this->container->singleton(
            AccountSelectionService::class,
            static function (ServiceContainer $container): AccountSelectionService {
                return new AccountSelectionService(
                    $container->get(AccountCatalogInterface::class),
                    $container->get(ActiveAccountStoreInterface::class)
                );
            }
        );

        $this->container->singleton(
            GoogleAdsAccountPanel::class,
            static function (ServiceContainer $container): GoogleAdsAccountPanel {
                return new GoogleAdsAccountPanel(
                    $container->get(AccountCatalogInterface::class),
                    $container->get(ActiveAccountStoreInterface::class),
                    $container->get(AccountSelectionService::class),
                    $container->get(Logger::class)
                );
            }
        );

        $this->container->singleton(
            RestController::class,
            static function (ServiceContainer $container): RestController {
                return new RestController(
                    $container->get(ConfigurationManager::class),
                    $container->get(HealthManager::class)
                );
            }
        );

        $this->container->singleton(
            DashboardPage::class,
            static function (ServiceContainer $container): DashboardPage {
                return new DashboardPage(
                    $container->get(ConfigurationManager::class),
                    $container->get(HealthManager::class),
                    $container->get(GoogleAdsConnectivityHealthCheck::class),
                    $container->get(GoogleAdsAccountPanel::class)
                );
            }
        );
    }
}
