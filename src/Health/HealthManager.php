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

/**
 * Provides the current system health status.
 */
final class HealthManager
{
    private ConfigurationManager $configuration;

    private EngineRegistry $engineRegistry;

    public function __construct(ConfigurationManager $configuration, EngineRegistry $engineRegistry)
    {
        $this->configuration = $configuration;
        $this->engineRegistry = $engineRegistry;
    }

    /**
     * Return basic system status.
     *
     * @return array{status: string, version: string, engines: int}
     */
    public function getStatus(): array
    {
        return array(
            'status'  => 'ok',
            'version' => $this->configuration->getVersion(),
            'engines' => $this->engineRegistry->count(),
        );
    }
}
