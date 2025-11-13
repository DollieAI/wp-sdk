<?php

namespace Dollie\SDK\Integrations\FluentBoards\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentBoards\App\Services\TaskService;

#[Action(
    id: 'fbs_remove_assignee_from_task',
    label: 'Remove Assignee from Task',
    since: '1.0.0'
)]
/**
 * RemoveAssigneeFromTask.
 * php version 5.6
 *
 * @category RemoveAssigneeFromTask
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RemoveAssigneeFromTask
 *
 * @category RemoveAssigneeFromTask
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RemoveAssigneeFromTask extends AutomateAction
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
    public $action = 'fbs_remove_assignee_from_task';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Remove Assignee from Task', 'dollie'),
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
     *
     * @return array|void
     *
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        if (! class_exists('FluentBoards\App\Services\TaskService')) {
            throw new Exception(__('FluentBoards TaskService not found.', 'dollie'));
        }

        if (! class_exists('\FluentBoards\App\Models\Task')) {
            throw new Exception(__('FluentBoards Task model not found.', 'dollie'));
        }

        $task_id = ! empty($selected_options['task_id']) ? sanitize_text_field($selected_options['task_id']) : null;
        $assignees = ! empty($selected_options['assignees']) ? sanitize_text_field($selected_options['assignees']) : null;

        if (! $task_id || ! $assignees) {
            throw new Exception(__('Task ID and assignees are required.', 'dollie'));
        }

        $assignee_ids = array_map('intval', explode(',', $assignees));

        $task_service = new TaskService();
        $task = \FluentBoards\App\Models\Task::find($task_id);

        if (! $task) {
            throw new Exception(__('Task not found.', 'dollie'));
        }

        foreach ($assignee_ids as $assignee_id) {
            if (method_exists($task_service, 'removeAssignee')) {
                $task_service->removeAssignee($assignee_id, $task);
            } else {
                $task->assignees()->detach($assignee_id);
            }
        }

        $task->load('assignees');

        return $task;
    }
}

RemoveAssigneeFromTask::get_instance();
