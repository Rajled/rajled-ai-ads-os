<?php
/**
 * Currency value object.
 *
 * @package Rajled\AiAdsOs\Domain\Common\ValueObject
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\ValueObject;

use Rajled\AiAdsOs\Domain\Common\Exception\InvalidCurrencyException;

/**
 * Represents a business currency code.
 */
final class Currency
{
    private string $code;

    public function __construct(string $code)
    {
        $code = strtoupper(trim($code));

        if ('' === $code) {
            throw new InvalidCurrencyException('Currency code cannot be empty.');
        }

        if (1 !== preg_match('/^[A-Z]{3}$/', $code)) {
            throw new InvalidCurrencyException('Currency code must be a three-letter alphabetic code.');
        }

        $this->code = $code;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function __toString(): string
    {
        return $this->code;
    }
}
