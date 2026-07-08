<?php
/**
 * Invalid identifier exception.
 *
 * @package Rajled\AiAdsOs\Domain\Common\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\Exception;

use InvalidArgumentException;

/**
 * Thrown when a domain identifier is invalid.
 */
final class InvalidIdentifierException extends InvalidArgumentException
{
}
