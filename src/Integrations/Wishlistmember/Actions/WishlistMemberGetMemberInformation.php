<?php

namespace Dollie\SDK\Integrations\Wishlistmember\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'wishlist_member_get_member_information',
    label: 'Get Member Information',
    since: '1.0.0'
)]
/**
 * WishlistMemberGetMemberInformation.
 * php version 5.6
 *
 * @category WishlistMemberGetMemberInformation
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WishlistMemberGetMemberInformation
 *
 * @category WishlistMemberGetMemberInformation
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WishlistMemberGetMemberInformation extends AutomateAction
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
    public $action = 'wishlist_member_get_member_information';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Get Member Information', 'dollie'),
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
        $member_id = $selected_options['wlm_members'];
        if (empty($member_id) || ! function_exists('wlmapi_get_member')) {
            return false;
        }
        $response = wlmapi_get_member($member_id);

        if ($response) {
            return $response;
        } else {
            return [
                'success' => false,
                'msg' => __('Failed to get a member information', 'dollie'),

            ];
        }
    }
}

WishlistMemberGetMemberInformation::get_instance();
