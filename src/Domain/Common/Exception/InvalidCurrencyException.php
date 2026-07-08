<?php
/**
 * Invalid currency exception.
 *
 * @package Rajled\AiAdsOs\Domain\Common\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\Exception;

use InvalidArgumentException;

/**
 * Thrown when a domain currency code is invalid.
 */
final class InvalidCurrencyException extends InvalidArgumentException
{
}
