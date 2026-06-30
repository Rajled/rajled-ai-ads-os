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

/**
 * Registers and renders the WordPress admin dashboard page.
 */
final class DashboardPage
{
    private const MENU_SLUG = 'rajled-ai-ads-os';

    private ConfigurationManager $configuration;

    private HealthManager $healthManager;

    public function __construct(ConfigurationManager $configuration, HealthManager $healthManager)
    {
        $this->configuration = $configuration;
        $this->healthManager = $healthManager;
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
        </div>
        <?php
    }
}
