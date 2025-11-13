<?php

namespace Dollie\SDK\Integrations\Wordpress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use WP_User;

#[Action(
    id: 'change_role',
    label: 'Role: Change the user',
    since: '1.0.0'
)]
/**
 * ChangeRole.
 * php version 5.6
 *
 * @category ChangeRole
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ChangeRole
 *
 * @category ChangeRole
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ChangeRole extends AutomateAction
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
    public $action = 'change_role';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __("Role: Change the user's role to a new role", 'dollie'),
            'action' => 'change_role',
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
     * @return array|bool
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $field = reset($fields);
        $user = new WP_User($user_id);

        if (! $user instanceof WP_User) {
            return [
                'status' => 'error',
                'message' => 'This user is not type of WP_User',
            ];
        }
        $current_roles = $user->roles;
        $specified_excluded_roles = [];
        if (! empty($selected_options['exclude_role'])) {
            $specified_excluded_roles = array_column($selected_options['exclude_role'], 'value');
        }
        $common_roles = array_values(array_intersect($specified_excluded_roles, $current_roles));
        if (empty($common_roles)) {
            $user->set_role($selected_options[$field['name']]);
        }

        return (array) $user;
    }
}

ChangeRole::get_instance();
