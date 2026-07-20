<?php
/**
 * Google Ads SDK failure classifier.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Google\Ads\GoogleAds\Lib\V24\GoogleAdsException;
use Google\Ads\GoogleAds\V24\Errors\ErrorCode;
use Google\ApiCore\ApiException;
use Google\ApiCore\ApiStatus;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Throwable;

/**
 * Converts native SDK exception chains into bounded, non-sensitive metadata.
 */
final class GoogleAdsSdkFailureClassifier
{
    private const GOOGLE_ADS_ERROR_CATEGORIES = array(
        'authentication_error',
        'authorization_error',
        'query_error',
        'request_error',
        'header_error',
        'customer_error',
        'customer_client_link_error',
        'customer_manager_link_error',
        'manager_link_error',
        'quota_error',
        'internal_error',
        'database_error',
    );

    private const AUTHENTICATION_ERROR_CATEGORIES = array(
        'authentication_error',
    );

    private const PERMISSION_ERROR_CATEGORIES = array(
        'authorization_error',
        'header_error',
        'customer_client_link_error',
        'customer_manager_link_error',
        'manager_link_error',
    );

    private const TRANSPORT_ERROR_CATEGORIES = array(
        'quota_error',
        'internal_error',
        'database_error',
    );

    private const TRANSPORT_RPC_STATUSES = array(
        ApiStatus::CANCELLED,
        ApiStatus::UNKNOWN,
        ApiStatus::DEADLINE_EXCEEDED,
        ApiStatus::RESOURCE_EXHAUSTED,
        ApiStatus::ABORTED,
        ApiStatus::INTERNAL,
        ApiStatus::UNAVAILABLE,
        ApiStatus::DATA_LOSS,
    );

    private const ALLOWED_RPC_STATUSES = array(
        ApiStatus::CANCELLED,
        ApiStatus::UNKNOWN,
        ApiStatus::INVALID_ARGUMENT,
        ApiStatus::DEADLINE_EXCEEDED,
        ApiStatus::NOT_FOUND,
        ApiStatus::ALREADY_EXISTS,
        ApiStatus::PERMISSION_DENIED,
        ApiStatus::RESOURCE_EXHAUSTED,
        ApiStatus::FAILED_PRECONDITION,
        ApiStatus::ABORTED,
        ApiStatus::OUT_OF_RANGE,
        ApiStatus::UNIMPLEMENTED,
        ApiStatus::INTERNAL,
        ApiStatus::UNAVAILABLE,
        ApiStatus::DATA_LOSS,
        ApiStatus::UNAUTHENTICATED,
    );

    private const MAX_GOOGLE_ADS_ERROR_CATEGORIES = 5;

    /**
     * @return array<string, string|list<string>>
     */
    public static function classify(
        Throwable $exception,
        string $stage,
        string $defaultCategory
    ): array {
        $rpcStatus = null;
        $googleAdsErrorCategories = array();
        $requestStatusCode = null;
        $transportFailureFound = false;
        $seenExceptions = array();
        $currentException = $exception;

        while ($currentException instanceof Throwable) {
            $exceptionId = spl_object_id($currentException);

            if (isset($seenExceptions[$exceptionId])) {
                break;
            }

            $seenExceptions[$exceptionId] = true;

            if ($currentException instanceof GoogleAdsException) {
                foreach ($currentException->getGoogleAdsFailure()->getErrors() as $googleAdsError) {
                    $errorCode = $googleAdsError->getErrorCode();

                    if (! $errorCode instanceof ErrorCode) {
                        continue;
                    }

                    $errorCategory = $errorCode->getErrorCode();

                    if (
                        is_string($errorCategory)
                        && in_array($errorCategory, self::GOOGLE_ADS_ERROR_CATEGORIES, true)
                    ) {
                        $googleAdsErrorCategories[$errorCategory] = true;
                    }
                }
            }

            if ($currentException instanceof ApiException) {
                $status = $currentException->getStatus();

                if (
                    null === $rpcStatus
                    && is_string($status)
                    && in_array($status, self::ALLOWED_RPC_STATUSES, true)
                ) {
                    $rpcStatus = $status;
                }
            }

            if ($currentException instanceof ConnectException) {
                $transportFailureFound = true;
            }

            if ($currentException instanceof RequestException) {
                $response = $currentException->getResponse();

                if (null === $response) {
                    $transportFailureFound = true;
                } elseif (null === $requestStatusCode) {
                    $requestStatusCode = $response->getStatusCode();
                }
            }

            $currentException = $currentException->getPrevious();
        }

        $errorCategories = array_keys($googleAdsErrorCategories);
        sort($errorCategories, SORT_STRING);
        $errorCategories = array_slice(
            $errorCategories,
            0,
            self::MAX_GOOGLE_ADS_ERROR_CATEGORIES
        );
        $category = self::classifyCategory(
            $stage,
            $defaultCategory,
            $rpcStatus,
            $errorCategories,
            $requestStatusCode,
            $transportFailureFound
        );
        $context = array(
            'sdk_failure_stage'    => $stage,
            'sdk_failure_category' => $category,
        );

        if (null !== $rpcStatus) {
            $context['rpc_status'] = $rpcStatus;
        }

        if (array() !== $errorCategories) {
            $context['google_ads_error_categories'] = $errorCategories;
        }

        return $context;
    }

    /**
     * @param list<string> $errorCategories
     */
    private static function classifyCategory(
        string $stage,
        string $defaultCategory,
        ?string $rpcStatus,
        array $errorCategories,
        ?int $requestStatusCode,
        bool $transportFailureFound
    ): string {
        if (
            ApiStatus::UNAUTHENTICATED === $rpcStatus
            || 401 === $requestStatusCode
            || self::containsAny($errorCategories, self::AUTHENTICATION_ERROR_CATEGORIES)
        ) {
            return GoogleAdsSdkException::CATEGORY_AUTHENTICATION;
        }

        if (
            self::isQueryStage($stage)
            && in_array('query_error', $errorCategories, true)
        ) {
            return GoogleAdsSdkException::CATEGORY_INVALID_QUERY;
        }

        if (
            ApiStatus::PERMISSION_DENIED === $rpcStatus
            || 403 === $requestStatusCode
            || self::containsAny($errorCategories, self::PERMISSION_ERROR_CATEGORIES)
        ) {
            return GoogleAdsSdkException::CATEGORY_PERMISSION_ACCESS;
        }

        if (
            $transportFailureFound
            || (null !== $requestStatusCode && $requestStatusCode >= 500)
            || (null !== $rpcStatus && in_array(
                $rpcStatus,
                self::TRANSPORT_RPC_STATUSES,
                true
            ))
            || self::containsAny($errorCategories, self::TRANSPORT_ERROR_CATEGORIES)
        ) {
            return GoogleAdsSdkException::CATEGORY_TRANSPORT_RPC;
        }

        return $defaultCategory;
    }

    private static function isQueryStage(string $stage): bool
    {
        return in_array(
            $stage,
            array(
                GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY,
                GoogleAdsSdkException::STAGE_MANAGER_HIERARCHY_QUERY,
            ),
            true
        );
    }

    /**
     * @param list<string> $values
     * @param list<string> $candidates
     */
    private static function containsAny(array $values, array $candidates): bool
    {
        return array() !== array_intersect($values, $candidates);
    }
}
