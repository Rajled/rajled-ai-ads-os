<?php
/**
 * Invalid status exception.
 *
 * @package Rajled\AiAdsOs\Domain\Common\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\Exception;

use InvalidArgumentException;

/**
 * Thrown when a domain status is invalid.
 */
final class InvalidStatusException extends InvalidArgumentException
{
}
