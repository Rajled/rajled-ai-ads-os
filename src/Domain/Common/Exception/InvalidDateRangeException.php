<?php
/**
 * Invalid date range exception.
 *
 * @package Rajled\AiAdsOs\Domain\Common\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\Exception;

use InvalidArgumentException;

/**
 * Thrown when a reporting date range is invalid.
 */
final class InvalidDateRangeException extends InvalidArgumentException
{
}
