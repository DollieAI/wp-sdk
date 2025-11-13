<?php

namespace Dollie\SDK\Integrations\Profilegrid\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\ProfileGrid\ProfileGrid;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'pg_user_removed_from_group',
    label: 'User Removed from Group',
    since: '1.0.0'
)]
/**
 * UserRemovedFromGroup.
 * php version 5.6
 *
 * @category UserRemovedFromGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserRemovedFromGroup
 *
 * @category UserRemovedFromGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserRemovedFromGroup
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ProfileGrid';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'pg_user_removed_from_group';

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
            'label' => __('User Removed from Group', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'pg_user_leave_group',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int $user_id User ID.
     * @param int $gid Group ID.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($user_id, $gid)
    {
        $context = WordPress::get_user_context($user_id);
        $context['group_id'] = $gid;
        $context = array_merge($context, ProfileGrid::pg_group_details($gid));
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
UserRemovedFromGroup::get_instance();
