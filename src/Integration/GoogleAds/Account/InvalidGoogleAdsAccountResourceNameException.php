<?php
/**
 * Invalid Google Ads account resource name exception.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Account;

use UnexpectedValueException;

/**
 * Thrown when an accessible customer resource name cannot be parsed safely.
 */
final class InvalidGoogleAdsAccountResourceNameException extends UnexpectedValueException
{
}
