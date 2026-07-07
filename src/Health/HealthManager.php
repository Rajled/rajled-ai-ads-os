<?php
/**
 * Health manager.
 *
 * @package Rajled\AiAdsOs\Health
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Health;

use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Engine\EngineRegistry;
use Rajled\AiAdsOs\Integration\IntegrationProviderInterface;
use Rajled\AiAdsOs\Integration\IntegrationRegistry;

/**
 * Provides the current system health status.
 */
final class HealthManager
{
    private ConfigurationManager $configuration;

    private EngineRegistry $engineRegistry;

    private ?IntegrationRegistry $integrationRegistry;

    public function __construct(
        ConfigurationManager $configuration,
        EngineRegistry $engineRegistry,
        IntegrationRegistry|IntegrationProviderInterface|null $integrations = null
    ) {
        $this->configuration = $configuration;
        $this->engineRegistry = $engineRegistry;
        $this->integrationRegistry = $this->resolveIntegrationRegistry($integrations);
    }

    /**
     * Return basic system status.
     *
     * @return array<string, mixed>
     */
    public function getStatus(): array
    {
        $status = array(
            'status'     => 'ok',
            'version'    => $this->configuration->getVersion(),
            'engines'    => $this->engineRegistry->count(),
        );

        return array_merge($status, $this->getIntegrationStatuses());
    }

    /**
     * Return registered integration provider statuses.
     *
     * @return array<string, array<string, mixed>>
     */
    private function getIntegrationStatuses(): array
    {
        if (null === $this->integrationRegistry) {
            return array();
        }

        return $this->integrationRegistry->getHealthStatuses();
    }

    private function resolveIntegrationRegistry(
        IntegrationRegistry|IntegrationProviderInterface|null $integrations
    ): ?IntegrationRegistry {
        if (null === $integrations) {
            return null;
        }

        if ($integrations instanceof IntegrationRegistry) {
            return $integrations;
        }

        $registry = new IntegrationRegistry();
        $registry->register($integrations);

        return $registry;
    }
}
