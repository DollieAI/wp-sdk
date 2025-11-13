<?php

declare(strict_types=1);

namespace Dollie\SDK\Integrations;

/**
 * Base AutomateAction Class
 *
 * Abstract base class for all automation actions
 */
abstract class AutomateAction
{
    /**
     * Integration ID
     *
     * @var string
     */
    public $integration;

    /**
     * Action ID
     *
     * @var string
     */
    public $action;

    /**
     * Register an action
     *
     * @param array $actions Actions array
     * @return array
     */
    abstract public function register($actions);

    /**
     * Action listener - the main execution method
     *
     * @param int $user_id User ID
     * @param int $automation_id Automation ID
     * @param array $fields Action fields/parameters
     * @param array $selected_options Selected options
     * @return array|object Response data
     */
    abstract public function _action_listener($user_id, $automation_id, $fields, $selected_options);

    /**
     * Get action ID
     *
     * @return string
     */
    public function get_action(): string
    {
        return $this->action;
    }

    /**
     * Get integration ID
     *
     * @return string
     */
    public function get_integration(): string
    {
        return $this->integration;
    }
}
