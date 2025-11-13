<?php

namespace Dollie\SDK\Integrations\Wordpress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'get_user_by_role',
    label: 'Get All Users By Role',
    since: '1.0.0'
)]
/**
 * GetUserByRole.
 * php version 5.6
 *
 * @category GetUserByRole
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GetUserByRole
 *
 * @category GetUserByRole
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetUserByRole extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WordPress';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'get_user_by_role';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get All Users By Role', 'dollie'),
            'action' => 'get_taxonomy_by_name',
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
     * @return array|string
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $user_role = $selected_options['role'];

        // Get all users by user role.
        $users = get_users(
            [
                'role' => $user_role,
                'orderby' => 'ID',
            ]
        );

        // Check if users are found.
        if (! $users) {
            return [
                'status' => 'error',
                'message' => 'No user with the selected role was found.',
            ];
        }

        return [
            $users,
        ];
    }
}

GetUserByRole::get_instance();
