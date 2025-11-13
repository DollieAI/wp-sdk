<?php

namespace Dollie\SDK\Integrations\FluentBoards\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentBoards\App\Services\TaskService;

#[Action(
    id: 'fbs_assign_task',
    label: 'Assign Task',
    since: '1.0.0'
)]
/**
 * AssignTask.
 * php version 5.6
 *
 * @category AssignTask
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AssignTask
 *
 * @category AssignTask
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AssignTask extends AutomateAction
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
    public $action = 'fbs_assign_task';

    /**
     * Register an action.
     *
     * @param array $actions Actions array.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Assign Task', 'dollie'),
            'action' => $this->action,
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id          User ID.
     * @param int   $automation_id    Automation ID.
     * @param array $fields           Fields data.
     * @param array $selected_options Selected options.
     *
     * @return array|void
     *
     * @throws Exception Exception if required data is missing.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! class_exists('FluentBoards\App\Services\TaskService')) {
            throw new Exception(__('FluentBoards TaskService not found.', 'dollie'));
        }

        if (! class_exists('\FluentBoards\App\Models\Task')) {
            throw new Exception(__('FluentBoards Task model not found.', 'dollie'));
        }

        // Validate input fields.
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
            $task_service->updateAssignee($assignee_id, $task);
        }

        $task->load('assignees');

        return $task;
    }
}

AssignTask::get_instance();
