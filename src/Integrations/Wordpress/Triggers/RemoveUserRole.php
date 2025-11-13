<?php

namespace Dollie\SDK\Integrations\Wordpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'remove_user_role',
    label: 'Role: Remove a role from the user',
    since: '1.0.0'
)]
/**
 * RemoveUserRole.
 * php version 5.6
 *
 * @category RemoveUserRole
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RemoveUserRole
 *
 * @category RemoveUserRole
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RemoveUserRole
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WordPress';

    /**
     * Action name.
     *
     * @var string
     */
    public $trigger = 'remove_user_role';

    /**
     * Constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register a action.
     *
     * @param array $triggers actions.
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Role: Remove a role from the user', 'dollie'),
            'action' => $this->trigger,
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int    $user_id user id.
     * @param string $role role.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($user_id, $role)
    {

        $context = WordPress::get_user_context($user_id);
        $context['role'] = $role;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

RemoveUserRole::get_instance();
