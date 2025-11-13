<?php

declare(strict_types=1);

namespace Dollie\SDK\Integrations;

/**
 * Base Integrations Class
 *
 * Abstract base class for all integrations
 */
abstract class BaseIntegration
{
    /**
     * Integration ID
     *
     * @var string
     */
    protected $id;

    /**
     * Integration name
     *
     * @var string
     */
    protected $name;

    /**
     * Triggers array
     *
     * @var array
     */
    protected $triggers = [];

    /**
     * Actions array
     *
     * @var array
     */
    protected $actions = [];

    /**
     * Integration constructor
     */
    public function __construct()
    {
        $this->id = $this->id;
        $this->name = $this->name;
        $this->triggers = $this->triggers;
        $this->actions = $this->actions;
    }

    /**
     * Get integration ID
     *
     * @return string
     */
    public function get_id(): string
    {
        return $this->id;
    }

    /**
     * Get integration name
     *
     * @return string
     */
    public function get_name(): string
    {
        return $this->name ?? $this->id;
    }

    /**
     * Get triggers
     *
     * @return array
     */
    public function get_triggers(): array
    {
        return $this->triggers;
    }

    /**
     * Get actions
     *
     * @return array
     */
    public function get_actions(): array
    {
        return $this->actions;
    }

    /**
     * Check if plugin is installed
     *
     * @return bool
     */
    abstract public function is_plugin_installed(): bool;
}
