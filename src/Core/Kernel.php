<?php
/**
 * Plugin kernel.
 *
 * @package Rajled\AiAdsOs\Core
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Core;

use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Dashboard\DashboardPage;
use Rajled\AiAdsOs\Engine\EngineRegistry;
use Rajled\AiAdsOs\Health\HealthManager;
use Rajled\AiAdsOs\Logging\Logger;
use Rajled\AiAdsOs\Rest\RestController;

/**
 * Coordinates framework initialization for the plugin.
 */
final class Kernel
{
    private ConfigurationManager $configuration;

    private EngineRegistry $engineRegistry;

    private Logger $logger;

    private HealthManager $healthManager;

    private RestController $restController;

    private DashboardPage $dashboardPage;

    private bool $booted = false;

    private function __construct(
        ConfigurationManager $configuration,
        EngineRegistry $engineRegistry,
        Logger $logger,
        HealthManager $healthManager,
        RestController $restController,
        DashboardPage $dashboardPage
    ) {
        $this->configuration = $configuration;
        $this->engineRegistry = $engineRegistry;
        $this->logger = $logger;
        $this->healthManager = $healthManager;
        $this->restController = $restController;
        $this->dashboardPage = $dashboardPage;
    }

    /**
     * Create a kernel with the default core services.
     *
     * @param array<string, mixed> $configurationValues Initial configuration values.
     */
    public static function create(array $configurationValues): self
    {
        $configuration = new ConfigurationManager($configurationValues);
        $engineRegistry = new EngineRegistry();
        $logger = new Logger();
        $healthManager = new HealthManager($configuration, $engineRegistry);

        return new self(
            $configuration,
            $engineRegistry,
            $logger,
            $healthManager,
            new RestController($configuration, $healthManager),
            new DashboardPage($configuration, $healthManager)
        );
    }

    /**
     * Register WordPress integrations.
     */
    public function boot(): void
    {
        if ($this->booted) {
            return;
        }

        add_action('rest_api_init', array($this->restController, 'registerRoutes'));
        add_action('admin_menu', array($this->dashboardPage, 'registerMenu'));

        $this->logger->info(
            'Kernel booted.',
            array(
                'version'        => $this->getVersion(),
                'rest_namespace' => $this->configuration->getRestNamespace(),
            )
        );

        $this->booted = true;
    }

    public function getVersion(): string
    {
        return $this->configuration->getVersion();
    }

    public function getConfiguration(): ConfigurationManager
    {
        return $this->configuration;
    }

    public function getEngineRegistry(): EngineRegistry
    {
        return $this->engineRegistry;
    }

    public function getHealthManager(): HealthManager
    {
        return $this->healthManager;
    }
}
