<?php
/**
 * Account selection candidate.
 *
 * @package Rajled\AiAdsOs\Application\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Application\Account;

use InvalidArgumentException;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Identifier;
use Rajled\AiAdsOs\Domain\Common\ValueObject\ResourceName;

/**
 * Represents a provider-neutral account available to the application.
 */
final class AccountCandidate
{
    private Identifier $identifier;

    private ResourceName $resourceName;

    private ?string $displayName;

    private bool $canManageAccounts;

    private ?Identifier $accessAccountIdentifier;

    public function __construct(
        Identifier $identifier,
        ResourceName $resourceName,
        ?string $displayName,
        bool $canManageAccounts,
        ?Identifier $accessAccountIdentifier
    ) {
        $displayName = null === $displayName ? null : trim($displayName);

        if ('' === $displayName) {
            $displayName = null;
        }

        if (
            null !== $accessAccountIdentifier
            && $identifier->getValue() === $accessAccountIdentifier->getValue()
        ) {
            throw new InvalidArgumentException(
                'An account cannot use its own identifier as its access account identifier.'
            );
        }

        $this->identifier = $identifier;
        $this->resourceName = $resourceName;
        $this->displayName = $displayName;
        $this->canManageAccounts = $canManageAccounts;
        $this->accessAccountIdentifier = $accessAccountIdentifier;
    }

    public function getIdentifier(): Identifier
    {
        return $this->identifier;
    }

    public function getResourceName(): ResourceName
    {
        return $this->resourceName;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function canManageAccounts(): bool
    {
        return $this->canManageAccounts;
    }

    public function getAccessAccountIdentifier(): ?Identifier
    {
        return $this->accessAccountIdentifier;
    }

    public function isSelectable(): bool
    {
        return ! $this->canManageAccounts;
    }
}
