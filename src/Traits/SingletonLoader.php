<?php

declare(strict_types=1);

namespace Dollie\SDK\Traits;

/**
 * Singleton Loader Trait
 *
 * Provides singleton pattern implementation for classes
 */
trait SingletonLoader
{
    /**
     * Instance of the class
     *
     * @var self|null
     */
    private static $instance = null;

    /**
     * Get instance of the class
     *
     * @return self
     */
    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Prevent cloning
     */
    private function __clone()
    {
    }

    /**
     * Prevent unserializing
     */
    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize singleton');
    }
}
