<?php

namespace Dollie\SDK\Integrations\Wordpress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'remove_user',
    label: 'User: Remove User',
    since: '1.0.0'
)]
/**
 * RemoveUser.
 * php version 5.6
 *
 * @category RemoveUser
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RemoveUser
 *
 * @category RemoveUser
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RemoveUser extends AutomateAction
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
    public $action = 'remove_user';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('User: Remove User', 'dollie'),
            'action' => 'remove_user',
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
     * @return array
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        $email = sanitize_email($selected_options['wp_user_email']);

        if (is_email($email)) {
            $user = get_user_by('email', $email);
            $data = $selected_options['delete_user_data'];
            if ($user) {
                require_once ABSPATH . 'wp-admin/includes/user.php';
                if ('yes' == $data) {
                    wp_delete_user($user->ID);
                } else {
                    /**
                     * Ignore line
                     *
                     * @phpstan-ignore-next-line
                     */
                    $admin = get_user_by('email', get_option('admin_email'));
                    /**
                     * Ignore line
                     *
                     * @phpstan-ignore-next-line
                     */
                    wp_delete_user($user->ID, $admin->ID);
                }

                $user_arr = [
                    'status' => esc_attr__('Success', 'dollie'),
                    'response' => esc_attr__('User deleted successfully.', 'dollie'),

                ];
            } else {
                $user_arr = [
                    'status' => esc_attr__('Error', 'dollie'),
                    'response' => esc_attr__('User not found.', 'dollie'),

                ];
            }
        } else {
            $user_arr = [
                'status' => esc_attr__('Error', 'dollie'),
                'response' => esc_attr__('Please enter valid email.', 'dollie'),

            ];
        }

        return $user_arr;
    }
}

RemoveUser::get_instance();
