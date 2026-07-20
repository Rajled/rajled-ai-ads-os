<?php
/**
 * Active account store contract.
 *
 * @package Rajled\AiAdsOs\Application\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Application\Account;

/**
 * Persists the account selected for application use.
 */
interface ActiveAccountStoreInterface
{
    public function get(): ?AccountCandidate;

    public function save(AccountCandidate $account): void;
}
