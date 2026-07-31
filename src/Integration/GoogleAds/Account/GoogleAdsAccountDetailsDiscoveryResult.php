<?php
/**
 * Google Ads account details discovery result.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Account;

use InvalidArgumentException;
use Rajled\AiAdsOs\Application\Account\DiscoveryCompleteness;

/**
 * Carries immutable Google Ads details and explicit discovery completeness.
 */
final class GoogleAdsAccountDetailsDiscoveryResult
{
    /**
     * @var list<GoogleAdsAccountDetails>
     */
    private array $details;

    private int $unavailableRootCount;

    private DiscoveryCompleteness $completeness;

    /**
     * @param list<GoogleAdsAccountDetails> $details
     */
    public function __construct(
        array $details,
        int $unavailableRootCount,
        DiscoveryCompleteness $completeness
    ) {
        if (! array_is_list($details)) {
            throw new InvalidArgumentException('Google Ads details must be provided as a list.');
        }

        foreach ($details as $accountDetails) {
            if (! $accountDetails instanceof GoogleAdsAccountDetails) {
                throw new InvalidArgumentException(
                    'Google Ads details must contain only account detail objects.'
                );
            }
        }

        if (
            $unavailableRootCount < 0
            || $unavailableRootCount > PHP_INT_MAX - count($details)
        ) {
            throw new InvalidArgumentException('Unavailable Google Ads root count is out of range.');
        }

        $this->validateState($details, $unavailableRootCount, $completeness);

        $this->details = $details;
        $this->unavailableRootCount = $unavailableRootCount;
        $this->completeness = $completeness;
    }

    /**
     * @return list<GoogleAdsAccountDetails>
     */
    public function getDetails(): array
    {
        return $this->details;
    }

    public function getUnavailableRootCount(): int
    {
        return $this->unavailableRootCount;
    }

    public function getCompleteness(): DiscoveryCompleteness
    {
        return $this->completeness;
    }

    /**
     * @param list<GoogleAdsAccountDetails> $details
     */
    private function validateState(
        array $details,
        int $unavailableRootCount,
        DiscoveryCompleteness $completeness
    ): void {
        $isEmpty = array() === $details;

        if (
            DiscoveryCompleteness::EMPTY === $completeness
            && (! $isEmpty || 0 !== $unavailableRootCount)
        ) {
            throw new InvalidArgumentException('Empty Google Ads discovery state is inconsistent.');
        }

        if (
            DiscoveryCompleteness::COMPLETE === $completeness
            && ($isEmpty || 0 !== $unavailableRootCount)
        ) {
            throw new InvalidArgumentException('Complete Google Ads discovery state is inconsistent.');
        }

        if (
            DiscoveryCompleteness::PARTIAL === $completeness
            && ($isEmpty || 0 === $unavailableRootCount)
        ) {
            throw new InvalidArgumentException('Partial Google Ads discovery state is inconsistent.');
        }
    }
}
