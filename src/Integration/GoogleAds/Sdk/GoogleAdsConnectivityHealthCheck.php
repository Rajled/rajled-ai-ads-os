<?php
/**
 * Google Ads connectivity health check.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

use Closure;
use Google\Ads\GoogleAds\Lib\V24\GoogleAdsException;
use Google\Ads\GoogleAds\V24\Errors\AuthenticationErrorEnum\AuthenticationError;
use Google\Ads\GoogleAds\V24\Errors\AuthorizationErrorEnum\AuthorizationError;
use Google\Ads\GoogleAds\V24\Errors\RequestErrorEnum\RequestError;
use Google\ApiCore\ApiException;
use Google\ApiCore\ApiStatus;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Rajled\AiAdsOs\Integration\GoogleAds\CredentialsManager;
use Rajled\AiAdsOs\Integration\GoogleAds\GoogleAdsConfiguration;
use Throwable;
use UnexpectedValueException;

/**
 * Runs one on-demand connectivity check and returns only sanitized health data.
 */
final class GoogleAdsConnectivityHealthCheck
{
    private CredentialsManager $credentialsManager;

    /**
     * @var Closure(): GoogleAdsConnectivityChecker
     */
    private Closure $checkerResolver;

    /**
     * @param callable(): GoogleAdsConnectivityChecker $checkerResolver
     */
    public function __construct(
        CredentialsManager $credentialsManager,
        callable $checkerResolver
    ) {
        $this->credentialsManager = $credentialsManager;
        $this->checkerResolver = Closure::fromCallable($checkerResolver);
    }

    /**
     * Run one connectivity verification.
     *
     * @return array<string, mixed>
     */
    public function run(): array
    {
        if (! $this->credentialsManager->hasRequiredCredentials()) {
            return $this->failureResult(
                'FAILED',
                'FAILED',
                'missing_configuration',
                $this->getMissingCredentialCategories()
            );
        }

        try {
            $checker = ($this->checkerResolver)();
        } catch (Throwable $exception) {
            return $this->failureResult(
                'PASSED',
                'FAILED',
                $this->classifyException($exception)
            );
        }

        try {
            $accessibleCustomers = $checker->check();
            $accessibleAccountCount = count($accessibleCustomers);
            unset($accessibleCustomers);
        } catch (Throwable $exception) {
            return $this->failureResult(
                'PASSED',
                'PASSED',
                $this->classifyException($exception)
            );
        }

        return array(
            'preflight'                => 'PASSED',
            'native_client'            => 'PASSED',
            'connectivity'             => 'PASSED',
            'accessible_account_count' => $accessibleAccountCount,
            'operation'                => 'ListAccessibleCustomers',
        );
    }

    /**
     * Return safe user-facing labels for missing required credentials.
     *
     * @return array<int, string>
     */
    private function getMissingCredentialCategories(): array
    {
        $labels = array(
            GoogleAdsConfiguration::DEVELOPER_TOKEN => 'Developer token',
            GoogleAdsConfiguration::CLIENT_ID       => 'OAuth client ID',
            GoogleAdsConfiguration::CLIENT_SECRET   => 'OAuth client secret',
            GoogleAdsConfiguration::REFRESH_TOKEN   => 'Refresh token',
        );
        $missingCategories = array();

        foreach ($this->credentialsManager->getMissingCredentialKeys() as $missingKey) {
            if (isset($labels[$missingKey])) {
                $missingCategories[] = $labels[$missingKey];
            }
        }

        return $missingCategories;
    }

    /**
     * Build a sanitized failure result without exception or request details.
     *
     * @param array<int, string> $missingCategories Safe missing credential labels.
     *
     * @return array<string, mixed>
     */
    private function failureResult(
        string $preflight,
        string $nativeClient,
        string $category,
        array $missingCategories = array()
    ): array {
        $messages = array(
            'missing_configuration'   => 'Required Google Ads configuration is incomplete.',
            'oauth_failure'           => 'Google OAuth authentication failed.',
            'invalid_developer_token' => 'The Google Ads developer token was rejected.',
            'developer_token_access'  => 'The Google Ads developer token does not have the required API access.',
            'network_failure'         => 'The hosting environment could not reach the Google Ads API.',
            'sdk_transport_failure'   => 'The Google Ads SDK transport failed.',
            'api_error'               => 'The Google Ads API rejected the connectivity request.',
            'unexpected_failure'      => 'The connectivity test failed unexpectedly.',
        );

        return array(
            'preflight'        => $preflight,
            'native_client'    => $nativeClient,
            'connectivity'     => 'FAILED',
            'failure_category' => $category,
            'message'          => $messages[$category] ?? $messages['unexpected_failure'],
            'missing'          => $missingCategories,
        );
    }

    private function classifyException(Throwable $exception): string
    {
        $currentException = $exception;
        $apiExceptionFound = false;

        while ($currentException instanceof Throwable) {
            if ($currentException instanceof GoogleAdsException) {
                $googleAdsCategory = $this->classifyGoogleAdsException($currentException);

                if (null !== $googleAdsCategory) {
                    return $googleAdsCategory;
                }

                $apiExceptionFound = true;
            }

            if ($currentException instanceof ConnectException) {
                return 'network_failure';
            }

            if ($currentException instanceof RequestException) {
                if (! $currentException->hasResponse()) {
                    return 'network_failure';
                }

                $response = $currentException->getResponse();
                $statusCode = null === $response ? 0 : $response->getStatusCode();

                if (400 === $statusCode || 401 === $statusCode) {
                    return 'oauth_failure';
                }

                if ($statusCode >= 500) {
                    return 'network_failure';
                }
            }

            if ($currentException instanceof ApiException) {
                $apiExceptionFound = true;
                $status = $currentException->getStatus();

                if (ApiStatus::UNAUTHENTICATED === $status) {
                    return 'oauth_failure';
                }

                if (in_array(
                    $status,
                    array(ApiStatus::UNAVAILABLE, ApiStatus::DEADLINE_EXCEEDED),
                    true
                )) {
                    return 'network_failure';
                }

                if (in_array(
                    $status,
                    array(ApiStatus::UNKNOWN, ApiStatus::INTERNAL, ApiStatus::DATA_LOSS),
                    true
                )) {
                    return 'sdk_transport_failure';
                }
            }

            if ($currentException instanceof UnexpectedValueException) {
                return 'unexpected_failure';
            }

            $currentException = $currentException->getPrevious();
        }

        return $apiExceptionFound ? 'api_error' : 'unexpected_failure';
    }

    private function classifyGoogleAdsException(GoogleAdsException $exception): ?string
    {
        $developerTokenAccessErrors = array(
            AuthorizationError::DEVELOPER_TOKEN_NOT_ON_ALLOWLIST,
            AuthorizationError::DEVELOPER_TOKEN_PROHIBITED,
            AuthorizationError::PROJECT_DISABLED,
            AuthorizationError::MISSING_TOS,
            AuthorizationError::DEVELOPER_TOKEN_NOT_APPROVED,
            AuthorizationError::SERVICE_ACCESS_DENIED,
            AuthorizationError::CLOUD_PROJECT_NOT_UNDER_ORGANIZATION,
        );
        $organizationAccessErrors = array(
            AuthenticationError::ORGANIZATION_NOT_RECOGNIZED,
            AuthenticationError::ORGANIZATION_NOT_APPROVED,
            AuthenticationError::ORGANIZATION_NOT_ASSOCIATED_WITH_DEVELOPER_TOKEN,
        );
        $developerTokenAccessFound = false;
        $oauthFailureFound = false;

        foreach ($exception->getGoogleAdsFailure()->getErrors() as $googleAdsError) {
            $errorCode = $googleAdsError->getErrorCode();

            if (null === $errorCode) {
                continue;
            }

            if ($errorCode->hasAuthenticationError()) {
                $authenticationError = $errorCode->getAuthenticationError();

                if (AuthenticationError::DEVELOPER_TOKEN_INVALID === $authenticationError) {
                    return 'invalid_developer_token';
                }

                if (in_array($authenticationError, $organizationAccessErrors, true)) {
                    $developerTokenAccessFound = true;
                } else {
                    $oauthFailureFound = true;
                }
            }

            if ($errorCode->hasAuthorizationError()
                && in_array(
                    $errorCode->getAuthorizationError(),
                    $developerTokenAccessErrors,
                    true
                )
            ) {
                $developerTokenAccessFound = true;
            }

            if ($errorCode->hasRequestError()
                && RequestError::DEVELOPER_TOKEN_PARAMETER_MISSING === $errorCode->getRequestError()
            ) {
                return 'invalid_developer_token';
            }
        }

        if ($developerTokenAccessFound) {
            return 'developer_token_access';
        }

        if ($oauthFailureFound) {
            return 'oauth_failure';
        }

        return null;
    }
}
