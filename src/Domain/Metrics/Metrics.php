<?php
/**
 * Metrics domain object.
 *
 * @package Rajled\AiAdsOs\Domain\Metrics
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Metrics;

use Rajled\AiAdsOs\Domain\Common\ValueObject\Money;
use Rajled\AiAdsOs\Domain\Metrics\Exception\InvalidMetricsException;

/**
 * Represents provider-independent measured advertising performance.
 */
final class Metrics
{
    private int $impressions;

    private int $clicks;

    private float $ctr;

    private Money $averageCpc;

    private Money $cost;

    private float $conversions;

    private Money $conversionValue;

    public function __construct(
        int $impressions,
        int $clicks,
        float $ctr,
        Money $averageCpc,
        Money $cost,
        float $conversions,
        Money $conversionValue
    ) {
        $this->validateState(
            $impressions,
            $clicks,
            $ctr,
            $averageCpc,
            $cost,
            $conversions,
            $conversionValue
        );

        $this->impressions = $impressions;
        $this->clicks = $clicks;
        $this->ctr = $ctr;
        $this->averageCpc = $averageCpc;
        $this->cost = $cost;
        $this->conversions = $conversions;
        $this->conversionValue = $conversionValue;
    }

    public function getImpressions(): int
    {
        return $this->impressions;
    }

    public function getClicks(): int
    {
        return $this->clicks;
    }

    public function getCtr(): float
    {
        return $this->ctr;
    }

    public function getAverageCpc(): Money
    {
        return $this->averageCpc;
    }

    public function getCost(): Money
    {
        return $this->cost;
    }

    public function getConversions(): float
    {
        return $this->conversions;
    }

    public function getConversionValue(): Money
    {
        return $this->conversionValue;
    }

    private function validateState(
        int $impressions,
        int $clicks,
        float $ctr,
        Money $averageCpc,
        Money $cost,
        float $conversions,
        Money $conversionValue
    ): void {
        if ($impressions < 0) {
            throw new InvalidMetricsException('Impressions cannot be negative.');
        }

        if ($clicks < 0) {
            throw new InvalidMetricsException('Clicks cannot be negative.');
        }

        if ($clicks > $impressions) {
            throw new InvalidMetricsException('Clicks cannot exceed impressions.');
        }

        if (! is_finite($ctr)) {
            throw new InvalidMetricsException('CTR must be a finite number.');
        }

        if ($ctr < 0.0 || $ctr > 1.0) {
            throw new InvalidMetricsException('CTR must be between 0 and 1.');
        }

        if (! is_finite($conversions)) {
            throw new InvalidMetricsException('Conversions must be a finite number.');
        }

        if ($conversions < 0.0) {
            throw new InvalidMetricsException('Conversions cannot be negative.');
        }

        $currency = $averageCpc->getCurrency()->getCode();

        if (
            $currency !== $cost->getCurrency()->getCode()
            || $currency !== $conversionValue->getCurrency()->getCode()
        ) {
            throw new InvalidMetricsException('Financial metrics must use the same currency.');
        }
    }
}
