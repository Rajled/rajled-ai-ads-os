<?php
/**
 * Google Ads WordPress active account store.
 *
 * @package Rajled\AiAdsOs\Infrastructure\WordPress\GoogleAds
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Infrastructure\WordPress\GoogleAds;

use InvalidArgumentException;
use Rajled\AiAdsOs\Application\Account\AccountCandidate;
use Rajled\AiAdsOs\Application\Account\ActiveAccountStoreInterface;
use Rajled\AiAdsOs\Application\Account\Exception\ActiveAccountStorageException;
use Rajled\AiAdsOs\Domain\Common\ValueObject\Identifier;
use Rajled\AiAdsOs\Domain\Common\ValueObject\ResourceName;
use Rajled\AiAdsOs\Logging\Logger;

/**
 * Persists a structurally valid Google Ads account as non-secret WordPress option data.
 */
final class GoogleAdsActiveAccountStore implements ActiveAccountStoreInterface
{
    public const OPTION_NAME = 'rajled_ai_ads_os_google_ads_active_account';

    private const PAYLOAD_KEYS = array(
        'identifier',
        'resource_name',
        'display_name',
        'can_manage_accounts',
        'access_account_identifier',
    );

    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function get(): ?AccountCandidate
    {
        $payload = get_option(self::OPTION_NAME, null);

        if (null === $payload) {
            return null;
        }

        if (! is_array($payload) || ! $this->isValidPayload($payload)) {
            $this->logMalformedStoredOption();

            return null;
        }

        try {
            return new AccountCandidate(
                new Identifier($payload['identifier']),
                new ResourceName($payload['resource_name']),
                $payload['display_name'],
                false,
                null === $payload['access_account_identifier']
                    ? null
                    : new Identifier($payload['access_account_identifier'])
            );
        } catch (InvalidArgumentException $exception) {
            $this->logMalformedStoredOption($exception);

            return null;
        }
    }

    public function save(AccountCandidate $account): void
    {
        $payload = $this->createPayload($account);

        if (! $this->isValidPayload($payload)) {
            throw new ActiveAccountStorageException(
                'The active Google Ads account is structurally invalid.'
            );
        }

        $currentPayload = get_option(self::OPTION_NAME, null);

        if ($payload === $currentPayload) {
            return;
        }

        if (update_option(self::OPTION_NAME, $payload, false)) {
            return;
        }

        if ($payload === get_option(self::OPTION_NAME, null)) {
            return;
        }

        throw new ActiveAccountStorageException(
            'Unable to persist the active Google Ads account selection.'
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function isValidPayload(array $payload): bool
    {
        $payloadKeys = array_keys($payload);
        $requiredKeys = self::PAYLOAD_KEYS;

        sort($payloadKeys, SORT_STRING);
        sort($requiredKeys, SORT_STRING);

        if ($payloadKeys !== $requiredKeys) {
            return false;
        }

        $identifier = $payload['identifier'];
        $resourceName = $payload['resource_name'];
        $displayName = $payload['display_name'];
        $canManageAccounts = $payload['can_manage_accounts'];
        $accessAccountIdentifier = $payload['access_account_identifier'];

        if (
            ! is_string($identifier)
            || 1 !== preg_match('/\A[0-9]{10}\z/', $identifier)
            || ! is_string($resourceName)
            || 'customers/' . $identifier !== $resourceName
            || (! is_string($displayName) && null !== $displayName)
            || false !== $canManageAccounts
        ) {
            return false;
        }

        if (null === $accessAccountIdentifier) {
            return true;
        }

        return is_string($accessAccountIdentifier)
            && 1 === preg_match('/\A[0-9]{10}\z/', $accessAccountIdentifier)
            && $identifier !== $accessAccountIdentifier;
    }

    /**
     * @return array<string, mixed>
     */
    private function createPayload(AccountCandidate $account): array
    {
        $accessAccountIdentifier = $account->getAccessAccountIdentifier();

        return array(
            'identifier'                => $account->getIdentifier()->getValue(),
            'resource_name'             => $account->getResourceName()->getValue(),
            'display_name'              => $account->getDisplayName(),
            'can_manage_accounts'       => $account->canManageAccounts(),
            'access_account_identifier' => null === $accessAccountIdentifier
                ? null
                : $accessAccountIdentifier->getValue(),
        );
    }

    private function logMalformedStoredOption(?InvalidArgumentException $exception = null): void
    {
        $context = array(
            'operation'        => 'google_ads_active_account_load',
            'outcome'          => 'rejected',
            'failure_category' => 'malformed_stored_option',
        );

        if (null !== $exception) {
            $context['exception_class'] = get_class($exception);
        }

        $this->logger->warning(
            'Stored Google Ads active account data was rejected.',
            $context
        );
    }
}
