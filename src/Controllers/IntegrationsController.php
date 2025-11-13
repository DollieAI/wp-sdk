<?php

declare(strict_types=1);

namespace Dollie\SDK\Controllers;

/**
 * Integrations Controller
 *
 * Manages registration and storage of integrations
 */
class IntegrationsController
{
    /**
     * Registered integrations
     *
     * @var array
     */
    private static $integrations = [];

    /**
     * Register an integration class
     *
     * @param string $className Full class name of the integration
     * @return void
     */
    public static function register(string $className): void
    {
        if (!class_exists($className)) {
            return;
        }

        // Get instance if it has get_instance method (Singleton)
        if (method_exists($className, 'get_instance')) {
            $instance = $className::get_instance();
        } else {
            $instance = new $className();
        }

        // Get integration ID
        $id = null;
        if (method_exists($instance, 'get_id')) {
            $id = $instance->get_id();
        } elseif (property_exists($instance, 'id')) {
            // Use reflection to access protected/private properties
            $reflection = new \ReflectionClass($instance);
            if ($reflection->hasProperty('id')) {
                $property = $reflection->getProperty('id');
                $property->setAccessible(true);
                $id = $property->getValue($instance);
            }
        }

        if ($id) {
            self::$integrations[$id] = $instance;
        }
    }

    /**
     * Get all registered integrations
     *
     * @return array
     */
    public static function get_integrations(): array
    {
        return self::$integrations;
    }

    /**
     * Get a specific integration by ID
     *
     * @param string $id Integration ID
     * @return object|null
     */
    public static function get_integration(string $id): ?object
    {
        return self::$integrations[$id] ?? null;
    }

    /**
     * Check if an integration is registered
     *
     * @param string $id Integration ID
     * @return bool
     */
    public static function is_registered(string $id): bool
    {
        return isset(self::$integrations[$id]);
    }
}
