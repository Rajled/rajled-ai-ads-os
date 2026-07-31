<?php
/**
 * Google Ads account details reader.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Google\Ads\GoogleAds\V24\Resources\Customer;
use Google\Ads\GoogleAds\V24\Resources\CustomerClient;
use Google\Ads\GoogleAds\V24\Services\GoogleAdsRow;
use Google\Ads\GoogleAds\V24\Services\SearchGoogleAdsRequest;
use Rajled\AiAdsOs\Application\Account\DiscoveryCompleteness;
use Rajled\AiAdsOs\Integration\GoogleAds\Account\AccessibleGoogleAdsAccount;
use Rajled\AiAdsOs\Integration\GoogleAds\Account\GoogleAdsAccountDetails;
use Rajled\AiAdsOs\Integration\GoogleAds\Account\GoogleAdsAccountDetailsDiscoveryResult;
use Throwable;

/**
 * Reads account names and hierarchy data through the native Google Ads SDK.
 */
final class GoogleAdsAccountDetailsReader
{
    private const ROOT_ACCOUNT_QUERY = 'SELECT '
        . 'customer.id, '
        . 'customer.resource_name, '
        . 'customer.descriptive_name, '
        . 'customer.manager '
        . 'FROM customer '
        . 'LIMIT 1';

    private const MANAGER_HIERARCHY_QUERY = 'SELECT '
        . 'customer_client.client_customer, '
        . 'customer_client.id, '
        . 'customer_client.descriptive_name, '
        . 'customer_client.manager, '
        . 'customer_client.level '
        . 'FROM customer_client';

    private GoogleAdsAccountDiscovery $accountDiscovery;

    private GoogleAdsSdkFactory $clientFactory;

    private GoogleAdsAccountDiscoveryFailurePolicy $failurePolicy;

    public function __construct(
        GoogleAdsAccountDiscovery $accountDiscovery,
        GoogleAdsSdkFactory $clientFactory,
        GoogleAdsAccountDiscoveryFailurePolicy $failurePolicy
    ) {
        $this->accountDiscovery = $accountDiscovery;
        $this->clientFactory = $clientFactory;
        $this->failurePolicy = $failurePolicy;
    }

    public function discover(): GoogleAdsAccountDetailsDiscoveryResult
    {
        $rootAccounts = $this->accountDiscovery->discover();

        if (array() === $rootAccounts) {
            return new GoogleAdsAccountDetailsDiscoveryResult(
                array(),
                0,
                DiscoveryCompleteness::EMPTY
            );
        }

        $details = array();
        $unavailableRootCount = 0;

        foreach ($rootAccounts as $rootAccount) {
            try {
                $rootClient = $this->requireClient(
                    $this->clientFactory->createForLoginCustomerId(
                        $rootAccount->getCustomerId()
                    )
                );
                $rootDetails = $this->readRootAccount($rootClient, $rootAccount);
                $details[] = $rootDetails;

                if (! $rootDetails->isManager()) {
                    continue;
                }

                foreach (
                    $this->readManagerHierarchy($rootClient, $rootDetails) as $clientDetails
                ) {
                    $details[] = $clientDetails;
                }
            } catch (GoogleAdsSdkException $exception) {
                if (! $this->failurePolicy->isRecoverableRootFailure($exception)) {
                    throw $exception;
                }

                $unavailableRootCount = $this->incrementUnavailableRootCount(
                    $unavailableRootCount
                );
                continue;
            }
        }

        if (array() === $details) {
            throw new GoogleAdsSdkException(
                'Google Ads account discovery did not return any usable accounts.',
                0,
                null,
                array(
                    'sdk_failure_stage'    => GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY,
                    'sdk_failure_category' => GoogleAdsSdkException::CATEGORY_PERMISSION_ACCESS,
                )
            );
        }

        return new GoogleAdsAccountDetailsDiscoveryResult(
            $details,
            $unavailableRootCount,
            0 === $unavailableRootCount
                ? DiscoveryCompleteness::COMPLETE
                : DiscoveryCompleteness::PARTIAL
        );
    }

    private function incrementUnavailableRootCount(int $unavailableRootCount): int
    {
        if (PHP_INT_MAX === $unavailableRootCount) {
            throw $this->responseException(
                'Google Ads returned too many unavailable account roots.',
                GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY
            );
        }

        return $unavailableRootCount + 1;
    }

    private function requireClient(?GoogleAdsSdkClient $client): GoogleAdsSdkClient
    {
        if (null === $client || ! $client->hasNativeClient()) {
            throw new GoogleAdsSdkException(
                'Google Ads PHP SDK client is not initialized.',
                0,
                null,
                array(
                    'sdk_failure_stage'    => GoogleAdsSdkException::STAGE_CLIENT_INITIALIZATION,
                    'sdk_failure_category' => GoogleAdsSdkException::CATEGORY_CLIENT_INITIALIZATION,
                )
            );
        }

        return $client;
    }

    private function readRootAccount(
        GoogleAdsSdkClient $client,
        AccessibleGoogleAdsAccount $rootAccount
    ): GoogleAdsAccountDetails {
        try {
            $request = SearchGoogleAdsRequest::build(
                $rootAccount->getCustomerId(),
                self::ROOT_ACCOUNT_QUERY
            );
            $response = $client
                ->getNativeClient()
                ->getGoogleAdsServiceClient()
                ->search($request);
            $customer = null;

            foreach ($response->iterateAllElements() as $row) {
                if (! $row instanceof GoogleAdsRow || null !== $customer) {
                    throw $this->responseException(
                        'Google Ads returned an invalid root account response.',
                        GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY
                    );
                }

                $customer = $row->getCustomer();
            }

            if (! $customer instanceof Customer) {
                throw $this->responseException(
                    'Google Ads returned an invalid root account response.',
                    GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY
                );
            }

            $customerId = $this->normalizeCustomerId(
                $customer->getId(),
                GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY
            );

            if ($rootAccount->getCustomerId() !== $customerId) {
                throw $this->responseException(
                    'Google Ads returned inconsistent root account data.',
                    GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY
                );
            }

            return new GoogleAdsAccountDetails(
                $this->normalizeResourceName(
                    $customer->getResourceName(),
                    $customerId,
                    GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY
                ),
                $customerId,
                $this->normalizeDescriptiveName(
                    $customer->getDescriptiveName(),
                    GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY
                ),
                $customer->getManager(),
                null,
                0
            );
        } catch (GoogleAdsSdkException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new GoogleAdsSdkException(
                'Unable to retrieve Google Ads root account details.',
                0,
                $exception,
                GoogleAdsSdkFailureClassifier::classify(
                    $exception,
                    GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY,
                    GoogleAdsSdkException::CATEGORY_ROOT_ACCOUNT_QUERY
                )
            );
        }
    }

    /**
     * @return list<GoogleAdsAccountDetails>
     */
    private function readManagerHierarchy(
        GoogleAdsSdkClient $client,
        GoogleAdsAccountDetails $rootAccount
    ): array {
        try {
            $request = SearchGoogleAdsRequest::build(
                $rootAccount->getCustomerId(),
                self::MANAGER_HIERARCHY_QUERY
            );
            $response = $client
                ->getNativeClient()
                ->getGoogleAdsServiceClient()
                ->search($request);
            $details = array();

            foreach ($response->iterateAllElements() as $row) {
                if (! $row instanceof GoogleAdsRow) {
                    throw $this->responseException(
                        'Google Ads returned an invalid manager hierarchy response.',
                        GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY
                    );
                }

                $customerClient = $row->getCustomerClient();

                if (! $customerClient instanceof CustomerClient) {
                    throw $this->responseException(
                        'Google Ads returned an invalid manager hierarchy response.',
                        GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY
                    );
                }

                $customerId = $this->normalizeCustomerId(
                    $customerClient->getId(),
                    GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY
                );
                $resourceName = $this->normalizeResourceName(
                    $customerClient->getClientCustomer(),
                    $customerId,
                    GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY
                );

                if ($rootAccount->getCustomerId() === $customerId) {
                    continue;
                }

                $hierarchyLevel = $this->normalizeHierarchyLevel(
                    $customerClient->getLevel(),
                    GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY
                );

                if (0 === $hierarchyLevel) {
                    throw $this->responseException(
                        'Google Ads returned an invalid manager hierarchy level.',
                        GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY
                    );
                }

                $details[] = new GoogleAdsAccountDetails(
                    $resourceName,
                    $customerId,
                    $this->normalizeDescriptiveName(
                        $customerClient->getDescriptiveName(),
                        GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY
                    ),
                    $customerClient->getManager(),
                    $rootAccount->getCustomerId(),
                    $hierarchyLevel
                );
            }

            return $details;
        } catch (GoogleAdsSdkException $exception) {
            throw $exception;
        } catch (Throwable $exception) {
            throw new GoogleAdsSdkException(
                'Unable to retrieve the Google Ads manager account hierarchy.',
                0,
                $exception,
                GoogleAdsSdkFailureClassifier::classify(
                    $exception,
                    GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY,
                    GoogleAdsSdkException::CATEGORY_MANAGER_HIERARCHY_QUERY
                )
            );
        }
    }

    private function normalizeCustomerId(mixed $customerId, string $stage): string
    {
        if (is_int($customerId)) {
            if ($customerId > 0) {
                return (string) $customerId;
            }
        } elseif (is_string($customerId)) {
            $customerId = trim($customerId);
            $validatedCustomerId = filter_var(
                $customerId,
                FILTER_VALIDATE_INT,
                array('options' => array('min_range' => 1))
            );

            if (false !== $validatedCustomerId) {
                return (string) $validatedCustomerId;
            }
        }

        throw $this->responseException(
            'Google Ads returned an invalid customer identifier.',
            $stage
        );
    }

    private function normalizeResourceName(
        mixed $resourceName,
        string $customerId,
        string $stage
    ): string
    {
        if (! is_string($resourceName)) {
            throw $this->responseException(
                'Google Ads returned an invalid customer resource name.',
                $stage
            );
        }

        $resourceName = trim($resourceName);
        $matches = array();

        if (
            1 !== preg_match('/\Acustomers\/([0-9]+)\z/', $resourceName, $matches)
            || $customerId !== $matches[1]
        ) {
            throw $this->responseException(
                'Google Ads returned an invalid customer resource name.',
                $stage
            );
        }

        return $resourceName;
    }

    private function normalizeDescriptiveName(mixed $descriptiveName, string $stage): ?string
    {
        if (! is_string($descriptiveName)) {
            throw $this->responseException(
                'Google Ads returned an invalid account descriptive name.',
                $stage
            );
        }

        $descriptiveName = trim($descriptiveName);

        return '' === $descriptiveName ? null : $descriptiveName;
    }

    private function normalizeHierarchyLevel(mixed $hierarchyLevel, string $stage): int
    {
        if (is_int($hierarchyLevel) && $hierarchyLevel >= 0) {
            return $hierarchyLevel;
        }

        if (is_string($hierarchyLevel)) {
            $hierarchyLevel = trim($hierarchyLevel);
            $validatedHierarchyLevel = filter_var(
                $hierarchyLevel,
                FILTER_VALIDATE_INT,
                array('options' => array('min_range' => 0))
            );

            if (false !== $validatedHierarchyLevel) {
                return $validatedHierarchyLevel;
            }
        }

        throw $this->responseException(
            'Google Ads returned an invalid manager hierarchy level.',
            $stage
        );
    }

    private function responseException(string $message, string $stage): GoogleAdsSdkException
    {
        return new GoogleAdsSdkException(
            $message,
            0,
            null,
            array(
                'sdk_failure_stage'    => $stage,
                'sdk_failure_category' => GoogleAdsSdkException::CATEGORY_MALFORMED_RESPONSE,
            )
        );
    }
}
