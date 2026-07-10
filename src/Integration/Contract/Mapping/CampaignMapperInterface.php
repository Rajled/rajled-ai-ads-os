<?php
/**
 * Campaign mapper contract.
 *
 * @package Rajled\AiAdsOs\Integration\Contract\Mapping
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\Contract\Mapping;

use Rajled\AiAdsOs\Domain\Campaign\Campaign;

/**
 * Maps provider-neutral campaign data into a Domain Campaign.
 */
interface CampaignMapperInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function map(array $data): Campaign;
}
