<?php

namespace Dollie\SDK\Integrations\Buddyboss\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'bb_add_user_to_group',
    label: 'Add User to Group',
    since: '1.0.0'
)]
/**
 * AddUserToGroup.
 * php version 5.6
 *
 * @category AddUserToGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddUserToGroup
 *
 * @category AddUserToGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddUserToGroup extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BuddyBoss';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'bb_add_user_to_group';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     *
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Add User to Group', 'dollie'),
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
     * @return mixed
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! function_exists('groups_join_group')) {
            return [
                'status' => 'error',
                'message' => __('BuddyBoss Groups join function not found.', 'dollie'),
            ];
        }
        $user_id = email_exists($selected_options['wp_user_email']);

        if (false === $user_id) {
            return [
                'status' => 'error',
                'message' => __('User with email does not exist: ', 'dollie') . $selected_options['wp_user_email'],
            ];
        }
        $context = WordPress::get_user_context($user_id);
        $groups = isset($selected_options['bb_group']) ? $selected_options['bb_group'] : [];
        $group_id = [];
        $group_name = [];
        if (! empty($groups)) {
            foreach ($groups as $group) {
                groups_join_group($group['value'], $user_id);
                $group_id[] = $group['value'];
                $group_name[] = $group['label'];
            }
        }
        $context['group'] = implode(',', $group_id);
        $context['group_name'] = implode(',', $group_name);

        return $context;
    }
}

AddUserToGroup::get_instance();
