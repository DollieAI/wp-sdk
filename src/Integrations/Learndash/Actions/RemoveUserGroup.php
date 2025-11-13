<?php

namespace Dollie\SDK\Integrations\Learndash\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\LearnDash\LearnDash;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'learndash_remove_user_group',
    label: 'Remove user from a group',
    since: '1.0.0'
)]
/**
 * RemoveUserGroup.
 * php version 5.6
 *
 * @category RemoveUserGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RemoveUserGroup
 *
 * @category RemoveUserGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RemoveUserGroup extends AutomateAction
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
    public $action = 'learndash_remove_user_group';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Remove user from a group', 'dollie'),
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
            $groups = learndash_get_users_group_ids($user_id);
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

        $removed_from_groups = [];

        // Remove user from groups.
        $count = 1;
        foreach ($groups as $group_id) {
            ld_update_group_access($user_id, $group_id, true);
            $arr_key = count($groups) > 1 ? 'group_' . $count : 'group';
            $removed_from_groups[$arr_key] = LearnDash::get_group_pluggable_data($group_id);
            $count++;
        }

        $user_data = LearnDash::get_user_pluggable_data($user_id);

        return [
            'user' => $user_data,
            'groups' => $removed_from_groups,
        ];
    }
}

RemoveUserGroup::get_instance();
