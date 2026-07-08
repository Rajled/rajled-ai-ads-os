<?php
/**
 * Date range value object.
 *
 * @package Rajled\AiAdsOs\Domain\Common\ValueObject
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\ValueObject;

use DateTimeImmutable;
use Rajled\AiAdsOs\Domain\Common\Exception\InvalidDateRangeException;

/**
 * Represents a reporting period.
 */
final class DateRange
{
    private DateTimeImmutable $startDate;

    private DateTimeImmutable $endDate;

    public function __construct(DateTimeImmutable $startDate, DateTimeImmutable $endDate)
    {
        if ($startDate > $endDate) {
            throw new InvalidDateRangeException('Date range start date cannot be after end date.');
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    public function contains(DateTimeImmutable $date): bool
    {
        return $date >= $this->startDate && $date <= $this->endDate;
    }

    public function __toString(): string
    {
        return $this->startDate->format('Y-m-d') . ' - ' . $this->endDate->format('Y-m-d');
    }
}
