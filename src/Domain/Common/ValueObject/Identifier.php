<?php
/**
 * Domain identifier value object.
 *
 * @package Rajled\AiAdsOs\Domain\Common\ValueObject
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\ValueObject;

use Rajled\AiAdsOs\Domain\Common\Exception\InvalidIdentifierException;

/**
 * Represents a generic business identifier.
 */
final class Identifier
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ('' === $value) {
            throw new InvalidIdentifierException('Identifier cannot be empty.');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
