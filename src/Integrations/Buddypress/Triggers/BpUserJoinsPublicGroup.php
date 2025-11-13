<?php

namespace Dollie\SDK\Integrations\Buddypress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'bp_user_joins_public_group',
    label: 'A user joins a public group',
    since: '1.0.0'
)]
/**
 * BpUserJoinsPublicGroup.
 * php version 5.6
 *
 * @category BpUserJoinsPublicGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * BpUserJoinsPublicGroup
 *
 * @category BpUserJoinsPublicGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class BpUserJoinsPublicGroup
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
    public $trigger = 'bp_user_joins_public_group';

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
            'label' => __('A user joins a public group', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'groups_join_group',
            'function' => [$this, 'trigger_listener'],
            'priority' => 60,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $group_id Group ID.
     * @param int    $user_id User ID.
     * @param object $group Group.
     * @return void
     */
    public function trigger_listener($group_id, $user_id, $group)
    {
        if (is_object($group)) {
            $group = get_object_vars($group);
        }
        $context['group'] = $group;
        $context['bp_public_group'] = $group_id;
        $context['creator'] = WordPress::get_user_context($user_id);

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
BpUserJoinsPublicGroup::get_instance();
