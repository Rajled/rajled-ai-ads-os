<?php
/**
 * Active account storage exception.
 *
 * @package Rajled\AiAdsOs\Application\Account\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Application\Account\Exception;

use RuntimeException;

/**
 * Raised when an active account cannot be persisted safely.
 */
final class ActiveAccountStorageException extends RuntimeException
{
}
