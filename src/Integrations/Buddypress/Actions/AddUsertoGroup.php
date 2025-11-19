<?php

namespace Dollie\SDK\Integrations\Buddypress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'add_user_to_group',
    label: 'Add user to group',
    since: '1.0.0'
)]
/**
 * AddUsertoGroup.
 * php version 5.6
 *
 * @category AddUsertoGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddUsertoGroup
 *
 * @category AddUsertoGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddUsertoGroup extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BuddyPress';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'add_user_to_group';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Add user to group', 'dollie'),
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
     * @throws Exception Exception.
     *
     * @return bool|array|void
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        $group_id = $selected_options['bp_group'];
        $user_email = $selected_options['wp_user_email'];

        if (is_email($user_email)) {
            $user = get_user_by('email', $user_email);

            if ($user) {
                $user_id = $user->ID;
                if (function_exists('groups_join_group')) {
                    if (function_exists('groups_get_group')) {
                        $group = groups_get_group($group_id);
                        $has_joined_groups = groups_join_group($group, $user_id);
                        if (true !== $has_joined_groups) {
                            return [
                                'status' => 'error',
                                'message' => 'Failed to add member into the group.',
                            ];
                        }
                    }
                }
                if (function_exists('groups_get_group')) {
                    $context = groups_get_group($group_id);
                    if (is_object($context)) {
                        $context = get_object_vars($context);
                    }

                    return array_merge(
                        WordPress::get_user_context($user_id),
                        $context
                    );
                }
            } else {
                // If there's no user found, return default message.
                return [
                    'status' => 'error',
                    'message' => 'User with the email provided not found.',
                ];
            }
        } else {
            return [
                'status' => 'error',
                'message' => 'Please enter valid email address.',
            ];
        }
    }
}

AddUsertoGroup::get_instance();
