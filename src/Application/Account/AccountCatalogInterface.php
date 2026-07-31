<?php
/**
 * Account catalog contract.
 *
 * @package Rajled\AiAdsOs\Application\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Application\Account;

/**
 * Provides accounts available for application selection.
 */
interface AccountCatalogInterface
{
    public function discover(): AccountDiscoveryResult;
}
