<?php
/**
 * Invalid money exception.
 *
 * @package Rajled\AiAdsOs\Domain\Common\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\Exception;

use InvalidArgumentException;

/**
 * Thrown when a monetary value is invalid.
 */
final class InvalidMoneyException extends InvalidArgumentException
{
}
