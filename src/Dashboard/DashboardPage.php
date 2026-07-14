<?php
/**
 * Admin dashboard placeholder.
 *
 * @package Rajled\AiAdsOs\Dashboard
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Dashboard;

use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Health\HealthManager;
use Rajled\AiAdsOs\Integration\GoogleAds\Sdk\GoogleAdsConnectivityHealthCheck;

/**
 * Registers and renders the WordPress admin dashboard page.
 */
final class DashboardPage
{
    private const MENU_SLUG = 'rajled-ai-ads-os';

    private const CONNECTIVITY_ACTION = 'run_google_ads_connectivity_test';

    private const CONNECTIVITY_NONCE_ACTION = 'rajled_google_ads_connectivity_test';

    private const CONNECTIVITY_NONCE_NAME = 'rajled_google_ads_connectivity_nonce';

    private ConfigurationManager $configuration;

    private HealthManager $healthManager;

    private GoogleAdsConnectivityHealthCheck $googleAdsConnectivityHealthCheck;

    public function __construct(
        ConfigurationManager $configuration,
        HealthManager $healthManager,
        GoogleAdsConnectivityHealthCheck $googleAdsConnectivityHealthCheck
    ) {
        $this->configuration = $configuration;
        $this->healthManager = $healthManager;
        $this->googleAdsConnectivityHealthCheck = $googleAdsConnectivityHealthCheck;
    }

    public function registerMenu(): void
    {
        add_menu_page(
            'RajLED AI Ads OS',
            'RajLED AI Ads OS',
            'manage_options',
            self::MENU_SLUG,
            array($this, 'render'),
            'dashicons-chart-line',
            56
        );
    }

    public function render(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        $health = $this->healthManager->getStatus();
        $connectivityResult = $this->handleConnectivityRequest();
        ?>
        <div class="wrap">
            <h1><?php echo esc_html($this->configuration->getPluginName()); ?></h1>

            <table class="widefat striped" style="max-width: 720px;">
                <tbody>
                    <tr>
                        <th scope="row"><?php echo esc_html__('Version', 'rajled-ai-ads-os'); ?></th>
                        <td><?php echo esc_html($health['version']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__('System Status', 'rajled-ai-ads-os'); ?></th>
                        <td><?php echo esc_html($health['status']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php echo esc_html__('REST Namespace', 'rajled-ai-ads-os'); ?></th>
                        <td><?php echo esc_html($this->configuration->getRestNamespace()); ?></td>
                    </tr>
                </tbody>
            </table>

            <h2><?php echo esc_html__('Google Ads Connectivity', 'rajled-ai-ads-os'); ?></h2>
            <p>
                <?php
                echo esc_html__(
                    'This read-only test calls only ListAccessibleCustomers and runs once after submission.',
                    'rajled-ai-ads-os'
                );
                ?>
            </p>

            <?php
            if (is_array($connectivityResult)) {
                $this->renderConnectivityResult($connectivityResult);
            }
            ?>

            <form
                method="post"
                action="<?php echo esc_url(admin_url('admin.php?page=' . self::MENU_SLUG)); ?>"
            >
                <?php
                wp_nonce_field(
                    self::CONNECTIVITY_NONCE_ACTION,
                    self::CONNECTIVITY_NONCE_NAME
                );
                ?>
                <input
                    type="hidden"
                    name="rajled_google_ads_connectivity_action"
                    value="<?php echo esc_attr(self::CONNECTIVITY_ACTION); ?>"
                >
                <?php
                submit_button(
                    esc_html__('Run connectivity test', 'rajled-ai-ads-os'),
                    'primary',
                    'submit',
                    false
                );
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Run the connectivity check only for the protected dashboard POST action.
     *
     * @return array<string, mixed>|null
     */
    private function handleConnectivityRequest(): ?array
    {
        $requestMethod = isset($_SERVER['REQUEST_METHOD']) && is_string($_SERVER['REQUEST_METHOD'])
            ? strtoupper(sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])))
            : '';

        if ('POST' !== $requestMethod) {
            return null;
        }

        $submittedAction = isset($_POST['rajled_google_ads_connectivity_action'])
            && is_string($_POST['rajled_google_ads_connectivity_action'])
            ? sanitize_key(wp_unslash($_POST['rajled_google_ads_connectivity_action']))
            : '';

        if (self::CONNECTIVITY_ACTION !== $submittedAction) {
            return null;
        }

        check_admin_referer(
            self::CONNECTIVITY_NONCE_ACTION,
            self::CONNECTIVITY_NONCE_NAME
        );

        return $this->googleAdsConnectivityHealthCheck->run();
    }

    /**
     * Render only sanitized connectivity result fields.
     *
     * @param array<string, mixed> $result Sanitized health check result.
     */
    private function renderConnectivityResult(array $result): void
    {
        $preflight = $this->normalizeStatus($result['preflight'] ?? null);
        $nativeClient = $this->normalizeStatus($result['native_client'] ?? null);
        $connectivity = $this->normalizeStatus($result['connectivity'] ?? null);
        ?>
        <table class="widefat striped" style="max-width: 720px; margin-bottom: 16px;">
            <tbody>
                <?php $this->renderConnectivityRow('Preflight', $preflight); ?>
                <?php $this->renderConnectivityRow('Native client', $nativeClient); ?>
                <?php $this->renderConnectivityRow('Connectivity', $connectivity); ?>

                <?php if ('PASSED' === $connectivity) : ?>
                    <?php
                    $accessibleAccountCount = isset($result['accessible_account_count'])
                        && is_int($result['accessible_account_count'])
                        ? $result['accessible_account_count']
                        : 0;
                    $this->renderConnectivityRow('Accessible accounts', $accessibleAccountCount);
                    $this->renderConnectivityRow('Operation', 'ListAccessibleCustomers');
                    ?>
                <?php else : ?>
                    <?php
                    $failureCategory = $this->normalizeFailureCategory(
                        $result['failure_category'] ?? null
                    );
                    $message = isset($result['message']) && is_string($result['message'])
                        ? $result['message']
                        : 'The connectivity test failed unexpectedly.';
                    $this->renderConnectivityRow('Failure category', $failureCategory);
                    $this->renderConnectivityRow('Message', $message);

                    $missingCategories = $this->normalizeMissingCategories(
                        $result['missing'] ?? array()
                    );

                    if (array() !== $missingCategories) {
                        $this->renderConnectivityRow(
                            'Missing configuration',
                            implode(', ', $missingCategories)
                        );
                    }
                    ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
    }

    private function renderConnectivityRow(string $label, string|int $value): void
    {
        ?>
        <tr>
            <th scope="row"><?php echo esc_html($label); ?></th>
            <td><?php echo esc_html((string) $value); ?></td>
        </tr>
        <?php
    }

    private function normalizeStatus(mixed $status): string
    {
        if (is_string($status) && in_array($status, array('PASSED', 'FAILED'), true)) {
            return $status;
        }

        return 'FAILED';
    }

    private function normalizeFailureCategory(mixed $category): string
    {
        $allowedCategories = array(
            'missing_configuration',
            'oauth_failure',
            'invalid_developer_token',
            'developer_token_access',
            'network_failure',
            'sdk_transport_failure',
            'api_error',
            'unexpected_failure',
        );

        if (is_string($category) && in_array($category, $allowedCategories, true)) {
            return $category;
        }

        return 'unexpected_failure';
    }

    /**
     * @return array<int, string>
     */
    private function normalizeMissingCategories(mixed $categories): array
    {
        if (! is_array($categories)) {
            return array();
        }

        $allowedCategories = array(
            'Developer token',
            'OAuth client ID',
            'OAuth client secret',
            'Refresh token',
        );

        return array_values(array_intersect($allowedCategories, $categories));
    }
}
