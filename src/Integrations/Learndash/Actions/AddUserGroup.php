<?php

namespace Dollie\SDK\Integrations\Learndash\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\LearnDash\LearnDash;
use Dollie\SDK\Traits\SingletonLoader;
use WP_Query;

#[Action(
    id: 'learndash_add_user_group',
    label: 'Add user to a group',
    since: '1.0.0'
)]
/**
 * AddUserGroup.
 * php version 5.6
 *
 * @category AddUserGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddUserGroup
 *
 * @category AddUserGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddUserGroup extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'LearnDash';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'learndash_add_user_group';

    /**
     * Register an action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Add user to a group', 'dollie'),
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
     * @psalm-suppress UndefinedFunction
     *
     * @return bool|array
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! $user_id) {
            return [
                'status' => 'error',
                'message' => __('User Not found', 'dollie'),

            ];
        }

        $group_id = (isset($selected_options['groups'])) ? $selected_options['groups'] : '';

        // Adding to all groups.
        if ('all' === $group_id) {
            // Get all groups.
            $query = new WP_Query(
                [
                    'post_type' => 'groups',
                    'post_status' => 'publish',
                    'fields' => 'ids',
                    'nopaging' => true, //phpcs:ignore
                ]
            );
            $groups = $query->get_posts();
        } else {
            $group = get_post((int) $group_id);

            // Bail if group doesn't exists.
            if (! $group) {
                return [
                    'status' => 'error',
                    'message' => __('No group is available ', 'dollie'),

                ];
            }

            $groups = [$group_id];
        }

        $added_to_groups = [];

        // Add user to groups.
        $count = 1;
        foreach ($groups as $group_id) {
            ld_update_group_access($user_id, $group_id);
            $arr_key = count($groups) > 1 ? 'group_' . $count : 'group';
            $added_to_groups[$arr_key] = LearnDash::get_group_pluggable_data($group_id);
            $count++;
        }

        $user_data = LearnDash::get_user_pluggable_data($user_id);

        return [
            'user' => $user_data,
            'groups' => $added_to_groups,
        ];
    }
}

AddUserGroup::get_instance();
