<?php

namespace Dollie\SDK\Integrations\Fluentcommunity\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'fc_user_leaves_space',
    label: 'User Leaves Space',
    since: '1.0.0'
)]
/**
 * UserLeavesSpace.
 * php version 5.6
 *
 * @category UserLeavesSpace
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserLeavesSpace
 *
 * @category UserLeavesSpace
 * @since    1.0.0
 */
class UserLeavesSpace
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentCommunity';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'fc_user_leaves_space';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register the trigger.
     *
     * @param array $triggers Existing triggers.
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('User Leaves Space', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'fluent_community/space/user_left',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener.
     *
     * @param object $space  The space object.
     * @param int    $user_id The user ID.
     * @param string $by     The action that triggered the join.
     * @return void
     */
    public function trigger_listener($space, $user_id, $by)
    {
        if (empty($space) || empty($user_id) || empty($by)) {
            return;
        }

        $context = [
            'space' => $space,
            'userId' => $user_id,
            'by' => $by,
            'user' => WordPress::get_user_context($user_id),
        ];

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

// Initialize the class.
UserLeavesSpace::get_instance();
