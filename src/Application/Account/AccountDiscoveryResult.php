<?php
/**
 * Account discovery result.
 *
 * @package Rajled\AiAdsOs\Application\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Application\Account;

use InvalidArgumentException;

/**
 * Carries immutable provider-neutral account discovery data and completeness.
 */
final class AccountDiscoveryResult
{
    /**
     * @var list<AccountCandidate>
     */
    private array $accounts;

    private int $unavailableRootCount;

    private DiscoveryCompleteness $completeness;

    /**
     * @param list<AccountCandidate> $accounts
     */
    public function __construct(
        array $accounts,
        int $unavailableRootCount,
        DiscoveryCompleteness $completeness
    ) {
        if (! array_is_list($accounts)) {
            throw new InvalidArgumentException('Discovered accounts must be provided as a list.');
        }

        foreach ($accounts as $account) {
            if (! $account instanceof AccountCandidate) {
                throw new InvalidArgumentException(
                    'Discovered accounts must contain only account candidates.'
                );
            }
        }

        if (
            $unavailableRootCount < 0
            || $unavailableRootCount > PHP_INT_MAX - count($accounts)
        ) {
            throw new InvalidArgumentException('Unavailable root count is out of range.');
        }

        $this->validateState($accounts, $unavailableRootCount, $completeness);

        $this->accounts = $accounts;
        $this->unavailableRootCount = $unavailableRootCount;
        $this->completeness = $completeness;
    }

    /**
     * @return list<AccountCandidate>
     */
    public function getAccounts(): array
    {
        return $this->accounts;
    }

    public function getUnavailableRootCount(): int
    {
        return $this->unavailableRootCount;
    }

    public function getCompleteness(): DiscoveryCompleteness
    {
        return $this->completeness;
    }

    public function isPartial(): bool
    {
        return DiscoveryCompleteness::PARTIAL === $this->completeness;
    }

    /**
     * @param list<AccountCandidate> $accounts
     */
    private function validateState(
        array $accounts,
        int $unavailableRootCount,
        DiscoveryCompleteness $completeness
    ): void {
        $isEmpty = array() === $accounts;

        if (
            DiscoveryCompleteness::EMPTY === $completeness
            && (! $isEmpty || 0 !== $unavailableRootCount)
        ) {
            throw new InvalidArgumentException('Empty discovery state is inconsistent.');
        }

        if (
            DiscoveryCompleteness::COMPLETE === $completeness
            && ($isEmpty || 0 !== $unavailableRootCount)
        ) {
            throw new InvalidArgumentException('Complete discovery state is inconsistent.');
        }

        if (
            DiscoveryCompleteness::PARTIAL === $completeness
            && ($isEmpty || 0 === $unavailableRootCount)
        ) {
            throw new InvalidArgumentException('Partial discovery state is inconsistent.');
        }
    }
}
