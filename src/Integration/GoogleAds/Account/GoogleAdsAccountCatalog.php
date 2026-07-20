<?php
/**
 * Google Ads account catalog.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Account;

use Rajled\AiAdsOs\Application\Account\AccountCatalogInterface;
use Rajled\AiAdsOs\Integration\GoogleAds\GoogleAdsConfiguration;
use Rajled\AiAdsOs\Integration\GoogleAds\Mapping\Exception\InvalidGoogleAdsMappingDataException;
use Rajled\AiAdsOs\Integration\GoogleAds\Mapping\GoogleAdsAccountMapper;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsAccountDetailsReader;

/**
 * Produces a deterministic, provider-neutral catalog from Google Ads hierarchy data.
 */
final class GoogleAdsAccountCatalog implements AccountCatalogInterface
{
    private GoogleAdsAccountDetailsReader $detailsReader;

    private GoogleAdsAccountMapper $accountMapper;

    private GoogleAdsConfiguration $configuration;

    public function __construct(
        GoogleAdsAccountDetailsReader $detailsReader,
        GoogleAdsAccountMapper $accountMapper,
        GoogleAdsConfiguration $configuration
    ) {
        $this->detailsReader = $detailsReader;
        $this->accountMapper = $accountMapper;
        $this->configuration = $configuration;
    }

    public function discover(): array
    {
        $accountsByCustomerId = $this->deduplicate(
            $this->detailsReader->discover(),
            $this->configuration->getLoginCustomerId()
        );
        $accounts = array();

        ksort($accountsByCustomerId, SORT_STRING);

        foreach ($accountsByCustomerId as $details) {
            $accounts[] = $this->accountMapper->map($details);
        }

        return $accounts;
    }

    /**
     * @param list<GoogleAdsAccountDetails> $detailsList
     *
     * @return array<string, GoogleAdsAccountDetails>
     */
    private function deduplicate(array $detailsList, string $configuredLoginCustomerId): array
    {
        $accounts = array();

        foreach ($detailsList as $details) {
            if (! $details instanceof GoogleAdsAccountDetails) {
                throw new InvalidGoogleAdsMappingDataException(
                    'Google Ads account discovery returned invalid detail data.'
                );
            }

            $customerId = $details->getCustomerId();

            if (! isset($accounts[$customerId])) {
                $accounts[$customerId] = $details;
                continue;
            }

            $current = $accounts[$customerId];
            $this->assertCompatibleDetails($current, $details);

            if ($this->isPreferredPath($details, $current, $configuredLoginCustomerId)) {
                $accounts[$customerId] = $details;
            }
        }

        return $accounts;
    }

    private function assertCompatibleDetails(
        GoogleAdsAccountDetails $current,
        GoogleAdsAccountDetails $candidate
    ): void {
        if (
            $current->getResourceName() !== $candidate->getResourceName()
            || $current->isManager() !== $candidate->isManager()
        ) {
            throw new InvalidGoogleAdsMappingDataException(
                'Google Ads returned conflicting account discovery data.'
            );
        }
    }

    private function isPreferredPath(
        GoogleAdsAccountDetails $candidate,
        GoogleAdsAccountDetails $current,
        string $configuredLoginCustomerId
    ): bool {
        $candidateLoginCustomerId = $candidate->getLoginCustomerId();
        $currentLoginCustomerId = $current->getLoginCustomerId();

        if (null === $candidateLoginCustomerId || null === $currentLoginCustomerId) {
            return null === $candidateLoginCustomerId && null !== $currentLoginCustomerId;
        }

        $candidateUsesConfiguredLogin = '' !== $configuredLoginCustomerId
            && $configuredLoginCustomerId === $candidateLoginCustomerId;
        $currentUsesConfiguredLogin = '' !== $configuredLoginCustomerId
            && $configuredLoginCustomerId === $currentLoginCustomerId;

        if ($candidateUsesConfiguredLogin !== $currentUsesConfiguredLogin) {
            return $candidateUsesConfiguredLogin;
        }

        if ($candidate->getHierarchyLevel() !== $current->getHierarchyLevel()) {
            return $candidate->getHierarchyLevel() < $current->getHierarchyLevel();
        }

        return strcmp($candidateLoginCustomerId, $currentLoginCustomerId) < 0;
    }
}
