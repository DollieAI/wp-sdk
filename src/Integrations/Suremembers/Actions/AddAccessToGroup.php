<?php

namespace Dollie\SDK\Integrations\Suremembers\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use SureMembers\Inc\Helper;

#[Action(
    id: 'add_access_to_group',
    label: 'Add User to Access Group',
    since: '1.0.0'
)]
/**
 * AddAccessToGroup.
 * php version 5.6
 *
 * @category AddAccessToGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddAccessToGroup
 *
 * @category AddAccessToGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddAccessToGroup extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'SureMembers';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'add_access_to_group';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Add User to Access Group', 'dollie'),
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
     * @psalm-suppress UndefinedMethod
     *
     * @return array|bool|void
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        if (! class_exists('SureMembers\Inc\Helper')) {
            return [
                'status' => 'error',
                'message' => __('SureMembers Helper class not found', 'dollie'),

            ];
        }
        if (! $user_id) {
            return [
                'status' => 'error',
                'message' => __('User Not found', 'dollie'),

            ];
        }
        $access_group_id = $selected_options['st_add_access_group'];

        if (empty($access_group_id)) {
            return;
        }

        $helper = new Helper();
        $helper->grant_access($user_id, $access_group_id);

        $context['group'] = WordPress::get_post_context($access_group_id);

        return $context;
    }
}

AddAccessToGroup::get_instance();
