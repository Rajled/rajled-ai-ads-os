<?php
/**
 * Invalid Google Ads mapping data exception.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Mapping\Exception
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Mapping\Exception;

use InvalidArgumentException;

/**
 * Thrown when provider-neutral Google Ads mapping data is structurally invalid.
 */
final class InvalidGoogleAdsMappingDataException extends InvalidArgumentException
{
}
