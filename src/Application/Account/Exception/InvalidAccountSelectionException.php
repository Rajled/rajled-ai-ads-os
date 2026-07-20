<?php
/**
 * Invalid account selection exception.
 *
 * @package Rajled\AiAdsOs\Application\Account\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Application\Account\Exception;

use RuntimeException;

/**
 * Raised when an account cannot be selected safely.
 */
final class InvalidAccountSelectionException extends RuntimeException
{
}
