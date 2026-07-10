<?php
/**
 * Invalid metrics exception.
 *
 * @package Rajled\AiAdsOs\Domain\Metrics\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Metrics\Exception;

use InvalidArgumentException;

/**
 * Thrown when metrics state is invalid.
 */
final class InvalidMetricsException extends InvalidArgumentException
{
}
