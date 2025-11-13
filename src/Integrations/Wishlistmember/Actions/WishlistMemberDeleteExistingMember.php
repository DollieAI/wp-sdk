<?php

namespace Dollie\SDK\Integrations\Wishlistmember\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'wishlist_member_delete_existing_member',
    label: 'Delete Existing Member',
    since: '1.0.0'
)]
/**
 * WishlistMemberDeleteExistingMember.
 * php version 5.6
 *
 * @category WishlistMemberDeleteExistingMember
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WishlistMemberDeleteExistingMember
 *
 * @category WishlistMemberDeleteExistingMember
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WishlistMemberDeleteExistingMember extends AutomateAction
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
    public $action = 'wishlist_member_delete_existing_member';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Delete Existing Member', 'dollie'),
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
        if (empty($member_id) || ! function_exists('wlmapi_delete_member')) {
            return false;
        }
        $response = wlmapi_delete_member($member_id);

        if ($response) {
            return array_merge(
                [
                    'success' => true,
                    'msg' => __('Member deleted successfully.', 'dollie'),

                ]
            );
        } else {
            return [
                'success' => false,
                'msg' => __('Failed to delete a member.', 'dollie'),

            ];
        }
    }
}

WishlistMemberDeleteExistingMember::get_instance();
