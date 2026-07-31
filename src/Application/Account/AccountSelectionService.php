<?php
/**
 * Account selection service.
 *
 * @package Rajled\AiAdsOs\Application\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Application\Account;

use Rajled\AiAdsOs\Application\Account\Exception\InvalidAccountSelectionException;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Identifier;

/**
 * Selects a canonical account returned by the current account catalog.
 */
final class AccountSelectionService
{
    private AccountCatalogInterface $accountCatalog;

    private ActiveAccountStoreInterface $activeAccountStore;

    public function __construct(
        AccountCatalogInterface $accountCatalog,
        ActiveAccountStoreInterface $activeAccountStore
    ) {
        $this->accountCatalog = $accountCatalog;
        $this->activeAccountStore = $activeAccountStore;
    }

    public function select(Identifier $identifier): AccountCandidate
    {
        $selectedAccount = null;
        $discoveryResult = $this->accountCatalog->discover();

        foreach ($discoveryResult->getAccounts() as $candidate) {
            if (! $candidate instanceof AccountCandidate) {
                throw new InvalidAccountSelectionException(
                    'Account discovery returned invalid selection data.'
                );
            }

            if ($identifier->getValue() !== $candidate->getIdentifier()->getValue()) {
                continue;
            }

            if (null !== $selectedAccount) {
                throw new InvalidAccountSelectionException(
                    'The requested account has ambiguous discovery data.'
                );
            }

            $selectedAccount = $candidate;
        }

        if (null === $selectedAccount) {
            throw new InvalidAccountSelectionException(
                'The requested account is not currently accessible.'
            );
        }

        if (! $selectedAccount->isSelectable()) {
            throw new InvalidAccountSelectionException(
                'Manager accounts cannot be selected as the active account.'
            );
        }

        $this->activeAccountStore->save($selectedAccount);

        return $selectedAccount;
    }
}
