<?php

namespace Dollie\SDK\Integrations\Wishlistmember\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'wishlist_member_update_existing_level',
    label: 'Update Existing Level',
    since: '1.0.0'
)]
/**
 * WishlistMemberUpdateExistingLevel.
 * php version 5.6
 *
 * @category WishlistMemberUpdateExistingLevel
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WishlistMemberUpdateExistingLevel
 *
 * @category WishlistMemberUpdateExistingLevel
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WishlistMemberUpdateExistingLevel extends AutomateAction
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
    public $action = 'wishlist_member_update_existing_level';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Update Existing Level', 'dollie'),
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
        $level_id = $selected_options['wlm_levels'];
        $name = $selected_options['level_name'];
        if (empty($level_id) || empty($name) || ! function_exists('wlmapi_update_level')) {
            return false;
        }
        $response = wlmapi_update_level(
            $level_id,
            [
                'name' => $name,
            ]
        );

        if ($response) {
            return $response;
        } else {
            return [
                'success' => false,
                'msg' => __('Failed to update a level', 'dollie'),

            ];
        }
    }
}

WishlistMemberUpdateExistingLevel::get_instance();
