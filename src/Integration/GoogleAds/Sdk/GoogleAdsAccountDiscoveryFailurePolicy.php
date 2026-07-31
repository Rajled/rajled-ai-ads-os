<?php
/**
 * Google Ads account discovery failure policy.
 *
 * @package Rajled\AiAdsOs\Integration\GoogleAds\Sdk
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Integration\GoogleAds\Sdk;

/**
 * Classifies SDK failures that can be isolated to one independently accessible root.
 */
final class GoogleAdsAccountDiscoveryFailurePolicy
{
    public function isRecoverableRootFailure(GoogleAdsSdkException $exception): bool
    {
        $context = $exception->getDiagnosticContext();

        return GoogleAdsSdkException::STAGE_ROOT_ACCOUNT_QUERY
                === ($context['sdk_failure_stage'] ?? null)
            && GoogleAdsSdkException::CATEGORY_PERMISSION_ACCESS
                === ($context['sdk_failure_category'] ?? null);
    }
}
