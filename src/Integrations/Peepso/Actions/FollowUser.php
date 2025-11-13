<?php

namespace Dollie\SDK\Integrations\Peepso\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use PeepSoUserFollower;

#[Action(
    id: 'peepso_follow_user',
    label: 'Follow User',
    since: '1.0.0'
)]
/**
 * FollowUser.
 * php version 5.6
 *
 * @category FollowUser
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * FollowUser
 *
 * @category FollowUser
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class FollowUser extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'PeepSo';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'peepso_follow_user';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Follow User', 'dollie'),
            'action' => $this->action,
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user_id.
     * @param int   $automation_id automation_id.
     * @param array $fields fields.
     * @param array $selected_options selectedOptions.
     *
     * @return array
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        if (! class_exists('PeepSoUserFollower')) {
            return [];
        }
        $follow_user_id = $selected_options['follow_user_id'];
        $userdata = get_userdata($follow_user_id);

        if (! $userdata) {
            return [
                'status' => 'error',
                'message' => "The user doesn't exist",
            ];
        }

        $follow = 1;
        $peepso_user_follower = new PeepSoUserFollower($follow_user_id, $user_id, true);
        $peepso_user_follower->set('follow', $follow);

        $context['follower'] = WordPress::get_user_context($user_id);
        $context['following'] = WordPress::get_user_context($follow_user_id);

        return $context;
    }
}

FollowUser::get_instance();
