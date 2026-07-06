<?php
/**
 * Service provider contract.
 *
 * @package Rajled\AiAdsOs\Core
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Core;

/**
 * Registers services in the application container.
 */
interface ServiceProviderInterface
{
    public function register(ServiceContainer $container): void;
}
