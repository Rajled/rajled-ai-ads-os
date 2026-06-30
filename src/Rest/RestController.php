<?php
/**
 * REST controller.
 *
 * @package Rajled\AiAdsOs\Rest
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Rest;

use Rajled\AiAdsOs\Config\ConfigurationManager;
use Rajled\AiAdsOs\Health\HealthManager;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Registers and serves the public framework REST endpoints.
 */
final class RestController
{
    private ConfigurationManager $configuration;

    private HealthManager $healthManager;

    public function __construct(ConfigurationManager $configuration, HealthManager $healthManager)
    {
        $this->configuration = $configuration;
        $this->healthManager = $healthManager;
    }

    public function registerRoutes(): void
    {
        register_rest_route(
            $this->configuration->getRestNamespace(),
            '/health',
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'getHealth'),
                'permission_callback' => array($this, 'canRead'),
            )
        );

        register_rest_route(
            $this->configuration->getRestNamespace(),
            '/version',
            array(
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => array($this, 'getVersion'),
                'permission_callback' => array($this, 'canRead'),
            )
        );
    }

    public function canRead(WP_REST_Request $request): bool
    {
        return true;
    }

    public function getHealth(WP_REST_Request $request): WP_REST_Response
    {
        return rest_ensure_response(
            $this->successResponse($this->healthManager->getStatus())
        );
    }

    public function getVersion(WP_REST_Request $request): WP_REST_Response
    {
        return rest_ensure_response(
            $this->successResponse(
                array(
                    'version' => $this->configuration->getVersion(),
                )
            )
        );
    }

    /**
     * @param array<string, mixed> $data Response payload.
     * @param array<string, mixed> $meta Response metadata.
     *
     * @return array{success: bool, data: array<string, mixed>, meta: array<string, mixed>, errors: array<int, mixed>}
     */
    private function successResponse(array $data, array $meta = array()): array
    {
        return array(
            'success' => true,
            'data'    => $data,
            'meta'    => $meta,
            'errors'  => array(),
        );
    }
}
