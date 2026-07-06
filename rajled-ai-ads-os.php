<?php
/**
 * Plugin Name: RajLED AI Ads OS
 * Description: Core framework for the RajLED AI Ads OS platform.
 * Version: 0.2.0-alpha.1
 * Author: RajLED
 * Text Domain: rajled-ai-ads-os
 * Requires PHP: 8.0
 *
 * @package Rajled\AiAdsOs
 */

declare(strict_types=1);

use Rajled\AiAdsOs\Core\Kernel;

if (! defined('ABSPATH')) {
    exit;
}

define('RAJLED_AI_ADS_OS_VERSION', '0.2.0-alpha.1');
define('RAJLED_AI_ADS_OS_FILE', __FILE__);
define('RAJLED_AI_ADS_OS_PATH', plugin_dir_path(__FILE__));
define('RAJLED_AI_ADS_OS_URL', plugin_dir_url(__FILE__));

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
        $kernel = Kernel::create(
            array(
                'plugin_name'    => 'RajLED AI Ads OS',
                'version'        => RAJLED_AI_ADS_OS_VERSION,
                'rest_namespace' => 'rajled-ai-ads/v1',
                'plugin_file'    => RAJLED_AI_ADS_OS_FILE,
                'plugin_path'    => RAJLED_AI_ADS_OS_PATH,
                'plugin_url'     => RAJLED_AI_ADS_OS_URL,
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
