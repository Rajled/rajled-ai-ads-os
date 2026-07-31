<?php
/**
 * Account discovery completeness.
 *
 * @package Rajled\AiAdsOs\Application\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Application\Account;

/**
 * Describes whether an account discovery result includes every available root.
 */
enum DiscoveryCompleteness: string
{
    case COMPLETE = 'complete';

    case PARTIAL = 'partial';

    case EMPTY = 'empty';
}
