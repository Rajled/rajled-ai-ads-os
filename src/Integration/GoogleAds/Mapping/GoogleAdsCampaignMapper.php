<?php
/**
 * Google Ads campaign mapper.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Mapping
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Mapping;

use Rajled\AiAdsOs\Domain\Campaign\Campaign;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Identifier;
use Rajled\AiAdsOs\Domain\Common\ValueObject\ResourceName;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Status;
use Rajled\AiAdsOs\Integration\Contract\Mapping\CampaignMapperInterface;
use Rajled\AiAdsOs\Integration\GoogleAds\Mapping\Exception\InvalidGoogleAdsMappingDataException;

/**
 * Maps provider-neutral Google Ads campaign data into a Domain Campaign.
 */
final class GoogleAdsCampaignMapper implements CampaignMapperInterface
{
    private const REQUIRED_KEYS = array(
        'id',
        'resource_name',
        'name',
        'status',
    );

    /**
     * @param array<string, mixed> $data
     */
    public function map(array $data): Campaign
    {
        $this->assertRequiredKeys($data);

        return new Campaign(
            new Identifier($this->getIdentifierValue($data, 'id')),
            new ResourceName($this->getStringValue($data, 'resource_name')),
            $this->getStringValue($data, 'name'),
            new Status($this->getStringValue($data, 'status'))
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function assertRequiredKeys(array $data): void
    {
        foreach (self::REQUIRED_KEYS as $key) {
            if (! array_key_exists($key, $data)) {
                throw new InvalidGoogleAdsMappingDataException(
                    sprintf('Missing required campaign mapping key: %s.', $key)
                );
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getIdentifierValue(array $data, string $key): string
    {
        $value = $data[$key];

        if (is_int($value) || is_string($value)) {
            return (string) $value;
        }

        throw new InvalidGoogleAdsMappingDataException(
            sprintf('Campaign mapping value "%s" must be a string or integer.', $key)
        );
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getStringValue(array $data, string $key): string
    {
        $value = $data[$key];

        if (is_string($value)) {
            return $value;
        }

        throw new InvalidGoogleAdsMappingDataException(
            sprintf('Campaign mapping value "%s" must be a string.', $key)
        );
    }
}
