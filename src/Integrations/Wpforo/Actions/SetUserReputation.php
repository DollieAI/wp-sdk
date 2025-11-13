<?php

namespace Dollie\SDK\Integrations\Wpforo\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'wp_foro_set_user_reputation',
    label: 'Set User Reputation',
    since: '1.0.0'
)]
/**
 * SetUserReputation.
 * php version 5.6
 *
 * @category SetUserReputation
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SetUserReputation
 *
 * @category SetUserReputation
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class SetUserReputation extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'wpForo';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'wp_foro_set_user_reputation';

    /**
     * Register an action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Set User Reputation', 'dollie'),
            'action' => $this->action,
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user id.
     * @param int   $automation_id automation_id.
     * @param array $fields template fields.
     * @param array $selected_options saved template data.
     * @throws Exception Exception.
     *
     * @return bool|array
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        $reputation_id = $selected_options['reputation_id'];

        $user_email = $selected_options['wp_user_email'];

        if (is_email($user_email)) {
            $user = get_user_by('email', $user_email);

            if ($user) {
                $user_id = $user->ID;
                if (function_exists('WPF')) {
                    $points = WPF()->member->rating($reputation_id, 'points');

                    $args = ['custom_points' => $points];

                    WPF()->member->update_profile_fields($user_id, $args, false);
                    WPF()->member->reset($user_id);
                    $user = WPF()->member->get_member($user_id);

                    return $user;
                } else {
                    return [
                        'status' => 'error',
                        'message' => 'Something went wrong.',
                    ];
                }
            } else {
                return [
                    'status' => 'error',
                    'message' => 'User not found.',
                ];
            }
        } else {
            $error = [
                'status' => esc_attr__('Error', 'dollie'),
                'response' => esc_attr__('Please enter valid email address.', 'dollie'),

            ];

            return $error;
        }
    }
}

SetUserReputation::get_instance();
