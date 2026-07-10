<?php
/**
 * Campaign domain entity.
 *
 * @package Rajled\AiAdsOs\Domain\Campaign
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Domain\Campaign;

use Rajled\AiAdsOs\Domain\Campaign\Exception\InvalidCampaignNameException;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Identifier;
use Rajled\AiAdsOs\Domain\Common\ValueObject\ResourceName;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Status;

/**
 * Represents a provider-independent marketing campaign.
 */
final class Campaign
{
    private Identifier $identifier;

    private ResourceName $resourceName;

    private string $name;

    private Status $status;

    public function __construct(
        Identifier $identifier,
        ResourceName $resourceName,
        string $name,
        Status $status
    ) {
        $this->identifier = $identifier;
        $this->resourceName = $resourceName;
        $this->name = $this->normalizeName($name);
        $this->status = $status;
    }

    public function getIdentifier(): Identifier
    {
        return $this->identifier;
    }

    public function getResourceName(): ResourceName
    {
        return $this->resourceName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function rename(string $name): void
    {
        $this->name = $this->normalizeName($name);
    }

    public function changeStatus(Status $status): void
    {
        $this->status = $status;
    }

    private function normalizeName(string $name): string
    {
        $name = trim($name);

        if ('' === $name) {
            throw new InvalidCampaignNameException('Campaign name cannot be empty.');
        }

        return $name;
    }
}
