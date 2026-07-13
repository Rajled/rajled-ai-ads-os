<?php
/**
 * Plugin Name: RajLED AI Ads OS
 * Description: Core framework for the RajLED AI Ads OS platform.
 * Version: 0.2.0-alpha.4
 * Author: RajLED
 * Text Domain: rajled-ai-ads-os
 * Requires PHP: 8.3
 *
 * @package Rajled\AiAdsOs
 */

declare(strict_types=1);

use Rajled\AiAdsOs\Core\Kernel;

if (! defined('ABSPATH')) {
    exit;
}

define('RAJLED_AI_ADS_OS_VERSION', '0.2.0-alpha.4');
define('RAJLED_AI_ADS_OS_FILE', __FILE__);
define('RAJLED_AI_ADS_OS_PATH', plugin_dir_path(__FILE__));
define('RAJLED_AI_ADS_OS_URL', plugin_dir_url(__FILE__));

$composerAutoloadPath = __DIR__ . '/vendor/autoload.php';

if (! is_readable($composerAutoloadPath)) {
    if (function_exists('add_action')) {
        add_action(
            'admin_notices',
            static function (): void {
                echo '<div class="notice notice-error"><p>';
                echo esc_html(
                    'RajLED AI Ads OS cannot start because Composer dependencies are missing. '
                    . 'Install them with Composer or deploy the complete release ZIP.'
                );
                echo '</p></div>';
            }
        );
    }

    return;
}

require_once $composerAutoloadPath;

spl_autoload_register(
    static function (string $className): void {
        $namespace = 'Rajled\\AiAdsOs\\';

        if (0 !== strncmp($namespace, $className, strlen($namespace))) {
            return;
        }

        $relativeClass = substr($className, strlen($namespace));
        $file = RAJLED_AI_ADS_OS_PATH . 'src/' . str_replace('\\', '/', $relativeClass) . '.php';

        if (is_readable($file)) {
            require_once $file;
        }
    }
);

/**
 * Return the plugin kernel instance.
 */
function rajled_ai_ads_os(): Kernel
{
    static $kernel = null;

    if (null === $kernel) {
        $googleAdsConstants = array(
            'developer_token'   => 'RAJLED_GOOGLE_ADS_DEVELOPER_TOKEN',
            'client_id'         => 'RAJLED_GOOGLE_ADS_CLIENT_ID',
            'client_secret'     => 'RAJLED_GOOGLE_ADS_CLIENT_SECRET',
            'refresh_token'     => 'RAJLED_GOOGLE_ADS_REFRESH_TOKEN',
            'login_customer_id' => 'RAJLED_GOOGLE_ADS_LOGIN_CUSTOMER_ID',
        );
        $googleAdsConfiguration = array();

        foreach ($googleAdsConstants as $configurationKey => $constantName) {
            $value = defined($constantName) ? constant($constantName) : '';
            $googleAdsConfiguration[$configurationKey] = is_scalar($value)
                ? trim((string) $value)
                : '';
        }

        $kernel = Kernel::create(
            array(
                'plugin_name'    => 'RajLED AI Ads OS',
                'version'        => RAJLED_AI_ADS_OS_VERSION,
                'rest_namespace' => 'rajled-ai-ads/v1',
                'plugin_file'    => RAJLED_AI_ADS_OS_FILE,
                'plugin_path'    => RAJLED_AI_ADS_OS_PATH,
                'plugin_url'     => RAJLED_AI_ADS_OS_URL,
                'google_ads'     => $googleAdsConfiguration,
            )
        );
    }

    return $kernel;
}

add_action(
    'plugins_loaded',
    static function (): void {
        rajled_ai_ads_os()->boot();
    }
);
