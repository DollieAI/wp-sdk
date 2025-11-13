<?php

namespace Dollie\SDK\Integrations\Buddypress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_sends_friend_request',
    label: 'A user sends a friendship request',
    since: '1.0.0'
)]
/**
 * UserSendsFriendRequest.
 * php version 5.6
 *
 * @category UserSendsFriendRequest
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserSendsFriendRequest
 *
 * @category UserSendsFriendRequest
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserSendsFriendRequest
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
    public $trigger = 'user_sends_friend_request';

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
            'label' => __('A user sends a friendship request', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'friends_friendship_requested',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $id User ID.
     * @param int    $initiator_user_id Initiator User ID.
     * @param int    $friend_user_id Friend User ID.
     * @param object $friendship Friendship.
     * @return void
     */
    public function trigger_listener($id, $initiator_user_id, $friend_user_id, $friendship)
    {

        $context['initiator'] = WordPress::get_user_context($initiator_user_id);
        $context['friend'] = WordPress::get_user_context($friend_user_id);

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $initiator_user_id,
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
UserSendsFriendRequest::get_instance();
