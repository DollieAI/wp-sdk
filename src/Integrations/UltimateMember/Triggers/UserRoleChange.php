<?php

namespace Dollie\SDK\Integrations\UltimateMember\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_role_change',
    label: 'User Role Change',
    since: '1.0.0'
)]
/**
 * UserRoleChange.
 * php version 5.6
 *
 * @category UserRoleChange
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserRoleChange
 *
 * @category UserRoleChange
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserRoleChange
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'UltimateMember';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_role_change';

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
     * Register action.
     *
     * @param array $triggers trigger data.
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('User Role Change', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'set_user_role',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $user_id User ID.
     * @param string $role Role.
     * @param string $old_roles Old Role.
     * @return void
     */
    public function trigger_listener($user_id, $role, $old_roles)
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

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
UserRoleChange::get_instance();
