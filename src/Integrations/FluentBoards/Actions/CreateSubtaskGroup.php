<?php

namespace Dollie\SDK\Integrations\FluentBoards\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentBoards\App\Models\Task;
use FluentBoardsPro\App\Services\SubtaskService;

#[Action(
    id: 'fbs_create_subtask_group',
    label: 'Create Subtask Group',
    since: '1.0.0'
)]
/**
 * CreateSubtaskGroup.
 * php version 5.6
 *
 * @category CreateSubtaskGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateSubtaskGroup
 *
 * @category CreateSubtaskGroup
 */
class CreateSubtaskGroup extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentBoards';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'fbs_create_subtask_group';

    /**
     * Register an action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Create Subtask Group', 'dollie'),
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
     * @param array $selected_options selected_options.
     * @return array|void
     *
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        $task_id = isset($selected_options['task_id']) ? sanitize_text_field($selected_options['task_id']) : '';
        $group_name = isset($selected_options['group_name']) ? sanitize_text_field($selected_options['group_name']) : '';

        if (empty($task_id) || empty($group_name)) {
            return [
                'status' => 'error',
                'message' => 'Required fields are missing: task_id or group_name.',
            ];
        }

        if (! class_exists('\FluentBoards\App\Models\Task')) {
            throw new Exception(__('FluentBoards Task model not found.', 'dollie'));
        }

        $task = Task::find($task_id);
        if (! $task) {
            return [
                'status' => 'error',
                'message' => 'Task not found.',
            ];
        }

        if (! class_exists('\FluentBoardsPro\App\Services\SubtaskService')) {
            return [
                'status' => 'error',
                'message' => 'SubtaskService class is not available.',
            ];
        }

        $subtask_service = new SubtaskService();
        $group_data = [
            'title' => $group_name,
        ];

        $group = $subtask_service->createSubtaskGroup($task_id, $group_data);

        if (empty($group)) {
            return [
                'status' => 'error',
                'message' => 'Failed to create subtask group.',
            ];
        }

        return [
            'id' => $group->id,
            'task_id' => $task_id,
            'group_name' => $group_name,
        ];
    }
}

CreateSubtaskGroup::get_instance();
