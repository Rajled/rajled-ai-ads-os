<?php
/**
 * Detailed Google Ads account result.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Account
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Account;

/**
 * Carries provider-specific account and access-path data from the SDK boundary.
 */
final class GoogleAdsAccountDetails
{
    private string $resourceName;

    private string $customerId;

    private ?string $descriptiveName;

    private bool $manager;

    private ?string $loginCustomerId;

    private int $hierarchyLevel;

    public function __construct(
        string $resourceName,
        string $customerId,
        ?string $descriptiveName,
        bool $manager,
        ?string $loginCustomerId,
        int $hierarchyLevel
    ) {
        $this->resourceName = $resourceName;
        $this->customerId = $customerId;
        $this->descriptiveName = $descriptiveName;
        $this->manager = $manager;
        $this->loginCustomerId = $loginCustomerId;
        $this->hierarchyLevel = $hierarchyLevel;
    }

    public function getResourceName(): string
    {
        return $this->resourceName;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function getDescriptiveName(): ?string
    {
        return $this->descriptiveName;
    }

    public function isManager(): bool
    {
        return $this->manager;
    }

    public function getLoginCustomerId(): ?string
    {
        return $this->loginCustomerId;
    }

    public function getHierarchyLevel(): int
    {
        return $this->hierarchyLevel;
    }
}
