<?php
/**
 * Google Ads account mapper.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Mapping
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Mapping;

use Rajled\AiAdsOs\Application\Account\AccountCandidate;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Identifier;
use Rajled\AiAdsOs\Domain\Common\ValueObject\ResourceName;
use Rajled\AiAdsOs\Integration\GoogleAds\Account\GoogleAdsAccountDetails;
use Rajled\AiAdsOs\Integration\GoogleAds\Mapping\Exception\InvalidGoogleAdsMappingDataException;
use Throwable;

/**
 * Maps Google Ads discovery data into the provider-neutral application model.
 */
final class GoogleAdsAccountMapper
{
    public function map(GoogleAdsAccountDetails $details): AccountCandidate
    {
        try {
            $loginCustomerId = $details->getLoginCustomerId();

            return new AccountCandidate(
                new Identifier($details->getCustomerId()),
                new ResourceName($details->getResourceName()),
                $details->getDescriptiveName(),
                $details->isManager(),
                null === $loginCustomerId ? null : new Identifier($loginCustomerId)
            );
        } catch (Throwable $exception) {
            throw new InvalidGoogleAdsMappingDataException(
                'Google Ads returned invalid account mapping data.',
                0,
                $exception
            );
        }
    }
}
