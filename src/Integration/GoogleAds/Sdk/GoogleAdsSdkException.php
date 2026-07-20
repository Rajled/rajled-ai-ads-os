<?php
/**
 * Google Ads SDK adapter exception.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use InvalidArgumentException;
use RuntimeException;
use Throwable;

/**
 * Represents SDK adapter failures isolated from the integration contract.
 */
final class GoogleAdsSdkException extends RuntimeException
{
    public const STAGE_CLIENT_INITIALIZATION = 'client_initialization';

    public const STAGE_LIST_ACCESSIBLE_CUSTOMERS = 'list_accessible_customers';

    public const STAGE_ROOT_ACCOUNT_QUERY = 'root_account_query';

    public const STAGE_MANAGER_HIERARCHY_QUERY = 'manager_hierarchy_query';

    public const CATEGORY_CLIENT_INITIALIZATION = 'client_initialization_failure';

    public const CATEGORY_LIST_ACCESSIBLE_CUSTOMERS = 'list_accessible_customers_failure';

    public const CATEGORY_ROOT_ACCOUNT_QUERY = 'root_account_query_failure';

    public const CATEGORY_MANAGER_HIERARCHY_QUERY = 'manager_hierarchy_query_failure';

    public const CATEGORY_PERMISSION_ACCESS = 'permission_access_failure';

    public const CATEGORY_INVALID_QUERY = 'invalid_query_failure';

    public const CATEGORY_AUTHENTICATION = 'authentication_failure';

    public const CATEGORY_TRANSPORT_RPC = 'transport_rpc_failure';

    public const CATEGORY_MALFORMED_RESPONSE = 'malformed_sdk_response';

    private const ALLOWED_DIAGNOSTIC_KEYS = array(
        'sdk_failure_stage',
        'sdk_failure_category',
        'rpc_status',
        'google_ads_error_categories',
    );

    private const ALLOWED_STAGES = array(
        self::STAGE_CLIENT_INITIALIZATION,
        self::STAGE_LIST_ACCESSIBLE_CUSTOMERS,
        self::STAGE_ROOT_ACCOUNT_QUERY,
        self::STAGE_MANAGER_HIERARCHY_QUERY,
    );

    private const ALLOWED_CATEGORIES = array(
        self::CATEGORY_CLIENT_INITIALIZATION,
        self::CATEGORY_LIST_ACCESSIBLE_CUSTOMERS,
        self::CATEGORY_ROOT_ACCOUNT_QUERY,
        self::CATEGORY_MANAGER_HIERARCHY_QUERY,
        self::CATEGORY_PERMISSION_ACCESS,
        self::CATEGORY_INVALID_QUERY,
        self::CATEGORY_AUTHENTICATION,
        self::CATEGORY_TRANSPORT_RPC,
        self::CATEGORY_MALFORMED_RESPONSE,
    );

    private const ALLOWED_RPC_STATUSES = array(
        'CANCELLED',
        'UNKNOWN',
        'INVALID_ARGUMENT',
        'DEADLINE_EXCEEDED',
        'NOT_FOUND',
        'ALREADY_EXISTS',
        'PERMISSION_DENIED',
        'RESOURCE_EXHAUSTED',
        'FAILED_PRECONDITION',
        'ABORTED',
        'OUT_OF_RANGE',
        'UNIMPLEMENTED',
        'INTERNAL',
        'UNAVAILABLE',
        'DATA_LOSS',
        'UNAUTHENTICATED',
    );

    private const ALLOWED_GOOGLE_ADS_ERROR_CATEGORIES = array(
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

    private const MAX_GOOGLE_ADS_ERROR_CATEGORIES = 5;

    /**
     * @var array<string, string|list<string>>
     */
    private array $diagnosticContext;

    /**
     * @param array<string, mixed> $diagnosticContext Sanitized diagnostic metadata.
     */
    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
        array $diagnosticContext = array()
    ) {
        $this->diagnosticContext = $this->validateDiagnosticContext($diagnosticContext);

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return array<string, string|list<string>>
     */
    public function getDiagnosticContext(): array
    {
        return $this->diagnosticContext;
    }

    /**
     * @param array<string, mixed> $diagnosticContext
     *
     * @return array<string, string|list<string>>
     */
    private function validateDiagnosticContext(array $diagnosticContext): array
    {
        foreach (array_keys($diagnosticContext) as $key) {
            if (! is_string($key) || ! in_array($key, self::ALLOWED_DIAGNOSTIC_KEYS, true)) {
                throw new InvalidArgumentException(
                    'Google Ads SDK diagnostic context contains an unsupported key.'
                );
            }
        }

        if (array() === $diagnosticContext) {
            return array();
        }

        if (
            ! isset($diagnosticContext['sdk_failure_stage'])
            || ! is_string($diagnosticContext['sdk_failure_stage'])
            || ! in_array(
                $diagnosticContext['sdk_failure_stage'],
                self::ALLOWED_STAGES,
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Google Ads SDK diagnostic context contains an invalid failure stage.'
            );
        }

        if (
            ! isset($diagnosticContext['sdk_failure_category'])
            || ! is_string($diagnosticContext['sdk_failure_category'])
            || ! in_array(
                $diagnosticContext['sdk_failure_category'],
                self::ALLOWED_CATEGORIES,
                true
            )
        ) {
            throw new InvalidArgumentException(
                'Google Ads SDK diagnostic context contains an invalid failure category.'
            );
        }

        if (array_key_exists('rpc_status', $diagnosticContext)) {
            if (
                ! is_string($diagnosticContext['rpc_status'])
                || ! in_array(
                    $diagnosticContext['rpc_status'],
                    self::ALLOWED_RPC_STATUSES,
                    true
                )
            ) {
                throw new InvalidArgumentException(
                    'Google Ads SDK diagnostic context contains an invalid RPC status.'
                );
            }
        }

        if (array_key_exists('google_ads_error_categories', $diagnosticContext)) {
            $errorCategories = $diagnosticContext['google_ads_error_categories'];

            if (
                ! is_array($errorCategories)
                || array_is_list($errorCategories) === false
                || count($errorCategories) > self::MAX_GOOGLE_ADS_ERROR_CATEGORIES
            ) {
                throw new InvalidArgumentException(
                    'Google Ads SDK diagnostic context contains invalid error categories.'
                );
            }

            foreach ($errorCategories as $errorCategory) {
                if (
                    ! is_string($errorCategory)
                    || ! in_array(
                        $errorCategory,
                        self::ALLOWED_GOOGLE_ADS_ERROR_CATEGORIES,
                        true
                    )
                ) {
                    throw new InvalidArgumentException(
                        'Google Ads SDK diagnostic context contains an invalid error category.'
                    );
                }
            }

            if (count($errorCategories) !== count(array_unique($errorCategories))) {
                throw new InvalidArgumentException(
                    'Google Ads SDK diagnostic context contains duplicate error categories.'
                );
            }
        }

        return $diagnosticContext;
    }
}
