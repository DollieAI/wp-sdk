<?php

namespace Dollie\SDK\Integrations\Wishlistmember\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'wishlist_member_get_specific_levels_details',
    label: 'Get Specific Level Details',
    since: '1.0.0'
)]
/**
 * WishlistMemberGetSpecificLevelDetails.
 * php version 5.6
 *
 * @category WishlistMemberGetSpecificLevelDetails
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WishlistMemberGetSpecificLevelDetails
 *
 * @category WishlistMemberGetSpecificLevelDetails
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WishlistMemberGetSpecificLevelDetails extends AutomateAction
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
    public $action = 'wishlist_member_get_specific_levels_details';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Get Specific Level Details', 'dollie'),
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
        if (empty($level_id) || ! function_exists('wlmapi_get_level')) {
            return false;
        }
        $response = wlmapi_get_level($level_id);
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

WishlistMemberGetSpecificLevelDetails::get_instance();
