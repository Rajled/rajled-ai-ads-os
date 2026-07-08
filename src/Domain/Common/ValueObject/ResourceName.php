<?php
/**
 * Resource name value object.
 *
 * @package Rajled\AiAdsOs\Domain\Common\ValueObject
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\ValueObject;

use Rajled\AiAdsOs\Domain\Common\Exception\InvalidResourceNameException;

/**
 * Represents an external provider resource identifier.
 */
final class ResourceName
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);

        if ('' === $value) {
            throw new InvalidResourceNameException('Resource name cannot be empty.');
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
