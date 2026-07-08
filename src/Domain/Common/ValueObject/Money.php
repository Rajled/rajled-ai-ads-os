<?php
/**
 * Money value object.
 *
 * @package Rajled\AiAdsOs\Domain\Common\ValueObject
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\ValueObject;

use Rajled\AiAdsOs\Domain\Common\Exception\InvalidMoneyException;

/**
 * Represents a non-negative monetary value.
 */
final class Money
{
    private float $amount;

    private Currency $currency;

    public function __construct(int|float|string $amount, Currency $currency)
    {
        if (! is_numeric($amount)) {
            throw new InvalidMoneyException('Money amount must be numeric.');
        }

        $amount = (float) $amount;

        if ($amount < 0) {
            throw new InvalidMoneyException('Money amount cannot be negative.');
        }

        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function __toString(): string
    {
        return (string) $this->amount . ' ' . (string) $this->currency;
    }
}
