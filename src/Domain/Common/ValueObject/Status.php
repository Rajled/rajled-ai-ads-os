<?php
/**
 * Status value object.
 *
 * @package Rajled\AiAdsOs\Domain\Common\ValueObject
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\ValueObject;

use Rajled\AiAdsOs\Domain\Common\Exception\InvalidStatusException;

/**
 * Represents a generic business status.
 */
final class Status
{
    public const ACTIVE = 'active';

    public const PAUSED = 'paused';

    public const REMOVED = 'removed';

    private const ALLOWED_VALUES = array(
        self::ACTIVE,
        self::PAUSED,
        self::REMOVED,
    );

    private string $value;

    public function __construct(string $value)
    {
        $value = strtolower(trim($value));

        if (! in_array($value, self::ALLOWED_VALUES, true)) {
            throw new InvalidStatusException('Status must be active, paused or removed.');
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isActive(): bool
    {
        return self::ACTIVE === $this->value;
    }

    public function isPaused(): bool
    {
        return self::PAUSED === $this->value;
    }

    public function isRemoved(): bool
    {
        return self::REMOVED === $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
