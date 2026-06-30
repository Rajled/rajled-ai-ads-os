<?php
/**
 * Logger service.
 *
 * @package Rajled\AiAdsOs\Logging
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Logging;

/**
 * Writes structured log entries to the WordPress debug log.
 */
final class Logger
{
    private const SOURCE = 'rajled-ai-ads-os';

    /**
     * @param array<string, mixed> $context Additional log context.
     */
    public function info(string $message, array $context = array()): void
    {
        $this->write('info', $message, $context);
    }

    /**
     * @param array<string, mixed> $context Additional log context.
     */
    public function warning(string $message, array $context = array()): void
    {
        $this->write('warning', $message, $context);
    }

    /**
     * @param array<string, mixed> $context Additional log context.
     */
    public function error(string $message, array $context = array()): void
    {
        $this->write('error', $message, $context);
    }

    /**
     * @param array<string, mixed> $context Additional log context.
     */
    private function write(string $level, string $message, array $context): void
    {
        if (! defined('WP_DEBUG') || ! WP_DEBUG) {
            return;
        }

        $entry = array(
            'timestamp' => gmdate('c'),
            'level'     => $level,
            'source'    => self::SOURCE,
            'message'   => $message,
            'context'   => $context,
        );

        $encodedEntry = function_exists('wp_json_encode')
            ? wp_json_encode($entry, JSON_UNESCAPED_SLASHES)
            : json_encode($entry, JSON_UNESCAPED_SLASHES);

        if (false === $encodedEntry) {
            $encodedEntry = sprintf(
                '[%s] %s: %s',
                self::SOURCE,
                strtoupper($level),
                $message
            );
        }

        error_log($encodedEntry);
    }
}
