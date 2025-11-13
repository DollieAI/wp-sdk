<?php

declare(strict_types=1);

namespace Dollie\SDK\Controllers;

/**
 * Automation Controller
 *
 * Handles trigger execution and automation workflows
 */
class AutomationController
{
    /**
     * Registered triggers
     *
     * @var array
     */
    private static $triggers = [];

    /**
     * Trigger listeners
     *
     * @var array
     */
    private static $listeners = [];

    /**
     * Handle trigger execution
     *
     * @param array $data Trigger data
     * @return void
     */
    public static function dollie_trigger_handle_trigger(array $data): void
    {
        if (!isset($data['trigger']) || !isset($data['context'])) {
            return;
        }

        $trigger = $data['trigger'];
        $context = $data['context'];

        // Fire the trigger
        self::fire_trigger($trigger, $context);

        // Execute registered listeners
        if (isset(self::$listeners[$trigger])) {
            foreach (self::$listeners[$trigger] as $listener) {
                if (is_callable($listener)) {
                    call_user_func($listener, $context);
                }
            }
        }

        // Apply WordPress filter if available
        if (function_exists('apply_filters')) {
            apply_filters('dollie_trigger_fired', $trigger, $context);
        }
    }

    /**
     * Register a trigger
     *
     * @param string $integration Integration ID
     * @param string $trigger Trigger ID
     * @param array $config Trigger configuration
     * @return void
     */
    public static function register_trigger(string $integration, string $trigger, array $config): void
    {
        if (!isset(self::$triggers[$integration])) {
            self::$triggers[$integration] = [];
        }

        self::$triggers[$integration][$trigger] = $config;

        // Register the WordPress action/filter if specified
        if (isset($config['action']) && function_exists('add_action')) {
            add_action(
                $config['action'],
                $config['function'] ?? '__return_null',
                $config['priority'] ?? 10,
                $config['accepted_args'] ?? 1
            );
        }
    }

    /**
     * Fire a trigger
     *
     * @param string $trigger Trigger ID
     * @param array $context Trigger context data
     * @return void
     */
    private static function fire_trigger(string $trigger, array $context): void
    {
        // Store trigger execution for logging/debugging
        if (!isset(self::$fired_triggers)) {
            self::$fired_triggers = [];
        }

        self::$fired_triggers[] = [
            'trigger' => $trigger,
            'context' => $context,
            'time' => time(),
        ];
    }

    /**
     * Add a listener for a trigger
     *
     * @param string $trigger Trigger ID
     * @param callable $callback Callback function
     * @return void
     */
    public static function add_listener(string $trigger, callable $callback): void
    {
        if (!isset(self::$listeners[$trigger])) {
            self::$listeners[$trigger] = [];
        }

        self::$listeners[$trigger][] = $callback;
    }

    /**
     * Get all registered triggers
     *
     * @return array
     */
    public static function get_triggers(): array
    {
        return self::$triggers;
    }

    /**
     * Get triggers for a specific integration
     *
     * @param string $integration Integration ID
     * @return array
     */
    public static function get_integration_triggers(string $integration): array
    {
        return self::$triggers[$integration] ?? [];
    }

    /**
     * Fired triggers log
     *
     * @var array
     */
    private static $fired_triggers = [];

    /**
     * Get fired triggers log
     *
     * @return array
     */
    public static function get_fired_triggers(): array
    {
        return self::$fired_triggers;
    }
}
