<?php
/**
 * Invalid resource name exception.
 *
 * @package Rajled\AiAdsOs\Domain\Common\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Common\Exception;

use InvalidArgumentException;

/**
 * Thrown when a provider resource name is invalid.
 */
final class InvalidResourceNameException extends InvalidArgumentException
{
}
