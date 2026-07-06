<?php
/**
 * Event dispatcher.
 *
 * @package Rajled\AiAdsOs\Core
 */

declare(strict_types=1);

namespace Rajled\AiAdsOs\Core;

use InvalidArgumentException;

/**
 * Dispatches internal application events without using WordPress hooks.
 */
final class EventDispatcher
{
    /**
     * @var array<string, array<int, callable>>
     */
    private array $listeners = array();

    public function listen(string $eventName, callable $listener): void
    {
        $normalizedEventName = $this->normalizeEventName($eventName);

        if (! isset($this->listeners[$normalizedEventName])) {
            $this->listeners[$normalizedEventName] = array();
        }

        $this->listeners[$normalizedEventName][] = $listener;
    }

    /**
     * @param array<string, mixed> $payload Event payload.
     */
    public function dispatch(string $eventName, array $payload = array()): void
    {
        $normalizedEventName = $this->normalizeEventName($eventName);

        foreach ($this->listeners[$normalizedEventName] ?? array() as $listener) {
            $listener($payload, $normalizedEventName);
        }
    }

    public function removeListener(string $eventName, callable $listener): void
    {
        $normalizedEventName = $this->normalizeEventName($eventName);

        if (! isset($this->listeners[$normalizedEventName])) {
            return;
        }

        foreach ($this->listeners[$normalizedEventName] as $index => $registeredListener) {
            if ($registeredListener === $listener) {
                unset($this->listeners[$normalizedEventName][$index]);
            }
        }

        $this->listeners[$normalizedEventName] = array_values($this->listeners[$normalizedEventName]);
    }

    public function clear(): void
    {
        $this->listeners = array();
    }

    private function normalizeEventName(string $eventName): string
    {
        $normalizedEventName = trim($eventName);

        if ('' === $normalizedEventName) {
            throw new InvalidArgumentException('Event name cannot be empty.');
        }

        return $normalizedEventName;
    }
}
