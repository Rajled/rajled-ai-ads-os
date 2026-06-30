<?php
/**
 * Engine contract.
 *
 * @package Rajled\AiAdsOs\Engine
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Engine;

/**
 * Defines the common lifecycle contract for future engines.
 */
interface EngineInterface
{
    public function register(): void;

    public function boot(): void;
}
