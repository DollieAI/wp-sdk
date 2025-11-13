<?php

namespace Dollie\SDK\Integrations\Buddypress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_updates_avatar',
    label: 'A user updates their avatar',
    since: '1.0.0'
)]
/**
 * UserUpdatesAvatar.
 * php version 5.6
 *
 * @category UserUpdatesAvatar
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserUpdatesAvatar
 *
 * @category UserUpdatesAvatar
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserUpdatesAvatar
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
    public $trigger = 'user_updates_avatar';

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
            'label' => __('A user updates their avatar', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'bp_members_avatar_uploaded',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $item_id User ID.
     * @param object $type New Rank.
     * @param object $avatar_data Old Rank.
     * @return void
     */
    public function trigger_listener($item_id, $type, $avatar_data)
    {

        $context['user'] = WordPress::get_user_context($item_id);
        $context['avatar'] = $avatar_data;
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
UserUpdatesAvatar::get_instance();
