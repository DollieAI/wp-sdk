<?php

namespace Dollie\SDK\Integrations\Buddypress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_request_access_private_group',
    label: 'A user requests access to a private group',
    since: '1.0.0'
)]
/**
 * UserRequestsAccessPrivateGroup.
 * php version 5.6
 *
 * @category UserRequestsAccessPrivateGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserRequestsAccessPrivateGroup
 *
 * @category UserRequestsAccessPrivateGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserRequestsAccessPrivateGroup
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BuddyPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_request_access_private_group';

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
            'label' => __('A user requests access to a private group', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'groups_membership_requested',
            'function' => [$this, 'trigger_listener'],
            'priority' => 60,
            'accepted_args' => 4,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $user_id User ID.
     * @param object $admins Admins.
     * @param int    $group_id Group ID.
     * @param int    $request_id Request ID.
     * @return void
     */
    public function trigger_listener($user_id, $admins, $group_id, $request_id)
    {

        if (function_exists('groups_get_group')) {
            $group = groups_get_group($group_id);
            if (is_object($group)) {
                $group = get_object_vars($group);
            }
            $context['group'] = $group;
            $context['bp_private_group'] = $group_id;
            $context['user'] = WordPress::get_user_context($user_id);
            $context['request'] = $request_id;
            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
UserRequestsAccessPrivateGroup::get_instance();
