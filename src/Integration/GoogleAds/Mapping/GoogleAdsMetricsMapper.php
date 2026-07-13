<?php
/**
 * Google Ads metrics mapper.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Mapping
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Mapping;

use Rajled\AiAdsOs\Domain\Common\ValueObject\Currency;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Money;
use Rajled\AiAdsOs\Domain\Metrics\Metrics;
use Rajled\AiAdsOs\Integration\Contract\Mapping\MetricsMapperInterface;
use Rajled\AiAdsOs\Integration\GoogleAds\Mapping\Exception\InvalidGoogleAdsMappingDataException;

/**
 * Maps provider-neutral Google Ads metrics data into Domain Metrics.
 */
final class GoogleAdsMetricsMapper implements MetricsMapperInterface
{
    private const REQUIRED_KEYS = array(
        'impressions',
        'clicks',
        'ctr',
        'average_cpc',
        'cost',
        'conversions',
        'conversion_value',
        'currency',
    );

    /**
     * @param array<string, mixed> $data
     */
    public function map(array $data): Metrics
    {
        $this->assertRequiredKeys($data);

        $currency = new Currency($this->getStringValue($data, 'currency'));

        return new Metrics(
            $this->getIntegerValue($data, 'impressions'),
            $this->getIntegerValue($data, 'clicks'),
            $this->getFloatValue($data, 'ctr'),
            new Money($this->getFloatValue($data, 'average_cpc'), $currency),
            new Money($this->getFloatValue($data, 'cost'), $currency),
            $this->getFloatValue($data, 'conversions'),
            new Money($this->getFloatValue($data, 'conversion_value'), $currency)
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
                    sprintf('Missing required metrics mapping key: %s.', $key)
                );
            }
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getIntegerValue(array $data, string $key): int
    {
        $value = $data[$key];

        if (is_int($value)) {
            return $value;
        }

        if (is_string($value)) {
            $value = trim($value);

            if (1 === preg_match('/^-?\d+$/', $value) && $this->isIntegerStringInRange($value)) {
                return (int) $value;
            }
        }

        throw new InvalidGoogleAdsMappingDataException(
            sprintf('Metrics mapping value "%s" must be an integer.', $key)
        );
    }

    private function isIntegerStringInRange(string $value): bool
    {
        $isNegative = '-' === substr($value, 0, 1);
        $digits = $isNegative ? substr($value, 1) : $value;
        $limit = $isNegative ? substr((string) PHP_INT_MIN, 1) : (string) PHP_INT_MAX;

        $digits = ltrim($digits, '0');

        if ('' === $digits) {
            return true;
        }

        if (strlen($digits) < strlen($limit)) {
            return true;
        }

        if (strlen($digits) > strlen($limit)) {
            return false;
        }

        return strcmp($digits, $limit) <= 0;
    }

    /**
     * @param array<string, mixed> $data
     */
    private function getFloatValue(array $data, string $key): float
    {
        $value = $data[$key];

        if (is_int($value) || is_float($value)) {
            $value = (float) $value;

            if (is_finite($value)) {
                return $value;
            }
        }

        if (is_string($value)) {
            $value = trim($value);

            if ('' !== $value && is_numeric($value)) {
                $value = (float) $value;

                if (is_finite($value)) {
                    return $value;
                }
            }
        }

        throw new InvalidGoogleAdsMappingDataException(
            sprintf('Metrics mapping value "%s" must be numeric.', $key)
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
            sprintf('Metrics mapping value "%s" must be a string.', $key)
        );
    }
}
