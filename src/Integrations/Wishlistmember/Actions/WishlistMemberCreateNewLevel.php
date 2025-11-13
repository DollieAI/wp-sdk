<?php

namespace Dollie\SDK\Integrations\Wishlistmember\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'wishlist_member_create_level',
    label: 'Create New Level',
    since: '1.0.0'
)]
/**
 * WishlistMemberCreateLevel.
 * php version 5.6
 *
 * @category WishlistMemberCreateLevel
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WishlistMemberCreateLevel
 *
 * @category WishlistMemberCreateLevel
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WishlistMemberCreateLevel extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WishlistMember';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'wishlist_member_create_level';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Create New Level', 'dollie'),
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
     * @return array|bool
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $name = $selected_options['level_name'];
        if (empty($name) || ! function_exists('wlmapi_create_level')) {
            return false;
        }
        $response = wlmapi_create_level(
            [
                'name' => $name,
                'registration_url' => '',
            ]
        );

        if ($response) {
            return $response;
        } else {
            return [
                'success' => false,
                'msg' => __('Failed to create a level', 'dollie'),

            ];
        }
    }
}

WishlistMemberCreateLevel::get_instance();
