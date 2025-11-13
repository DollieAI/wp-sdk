<?php

namespace Dollie\SDK\Integrations\Buddyboss\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_sends_friendship_request',
    label: 'User Sends Request',
    since: '1.0.0'
)]
/**
 * UserSendFriendshipRequest.
 * php version 5.6
 *
 * @category UserSendFriendshipRequest
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserSendFriendshipRequest
 *
 * @category UserSendFriendshipRequest
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserSendFriendshipRequest
{
    use SingletonLoader;

    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BuddyBoss';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_sends_friendship_request';

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
     * @param array $triggers triggers.
     *
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('User Sends Request', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'friends_friendship_after_save',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     *  Trigger listener
     *
     * @param object $friendship_obj friendship obj.
     *
     * @return void
     */
    public function trigger_listener($friendship_obj)
    {
        if (! property_exists($friendship_obj, 'initiator_user_id') || ! property_exists($friendship_obj, 'friend_user_id')) {
            return;
        }
        $initiator_id = $friendship_obj->initiator_user_id;
        $friend_id = $friendship_obj->friend_user_id;

        $context = WordPress::get_user_context($initiator_id);

        $friend_context = WordPress::get_user_context($friend_id);

        $avatar = get_avatar_url($initiator_id);

        $context['avatar_url'] = ($avatar) ? $avatar : '';

        $context['friend_id'] = $friend_id;
        $context['friend_first_name'] = $friend_context['user_firstname'];
        $context['friend_last_name'] = $friend_context['user_lastname'];
        $context['friend_email'] = $friend_context['user_email'];

        $friend_avatar = get_avatar_url($friend_id);
        $context['friend_avatar_url'] = $friend_avatar;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserSendFriendshipRequest::get_instance();
