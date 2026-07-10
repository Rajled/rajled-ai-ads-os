<?php
/**
 * Metrics mapper contract.
 *
 * @package Rajled\AiAdsOs\Integration\Contract\Mapping
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\Contract\Mapping;

use Rajled\AiAdsOs\Domain\Metrics\Metrics;

/**
 * Maps provider-neutral metrics data into Domain Metrics.
 */
interface MetricsMapperInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function map(array $data): Metrics;
}
