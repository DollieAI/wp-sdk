<?php

namespace Dollie\SDK\Integrations\Peepso\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'peepso_user_loses_follower',
    label: 'User Loses Follower',
    since: '1.0.0'
)]
/**
 * UserLosesFollower.
 * php version 5.6
 *
 * @category UserLosesFollower
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserLosesFollower
 *
 * @category UserLosesFollower
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserLosesFollower
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'PeepSo';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'peepso_user_loses_follower';

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
            'label' => __('User Loses Follower', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'peepso_ajax_start',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $data Data.
     *
     * @return void
     */
    public function trigger_listener($data)
    {
        $post_data = $_POST; // @codingStandardsIgnoreLine
        if ('followerajax.set_follow_status' !== $data) {
            return;
        }

        $follower_id = $post_data['uid'];
        $user_id = $post_data['user_id'];
        $follow_status = $post_data['follow'];

        if ($follow_status) {
            return;
        }

        if (false === $follower_id) {
            return;
        }

        $context['follower_user'] = WordPress::get_user_context($user_id);
        $context['following_user'] = WordPress::get_user_context($follower_id);

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserLosesFollower::get_instance();
