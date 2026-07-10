<?php
/**
 * Invalid campaign name exception.
 *
 * @package Rajled\AiAdsOs\Domain\Campaign\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Campaign\Exception;

use InvalidArgumentException;

/**
 * Thrown when a campaign name is invalid.
 */
final class InvalidCampaignNameException extends InvalidArgumentException
{
}
