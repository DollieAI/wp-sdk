<?php

namespace Dollie\SDK\Integrations\FluentBoards\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentBoards\App\Services\BoardService;

#[Action(
    id: 'fbs_add_user_to_board',
    label: 'Add User to Board',
    since: '1.0.0'
)]
/**
 * AddUserToBoard.
 * php version 5.6
 *
 * @category AddUserToBoard
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddUserToBoard
 *
 * @category AddUserToBoard
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddUserToBoard extends AutomateAction
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
    public $action = 'fbs_add_user_to_board';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Add User to Board', 'dollie'),
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
        $board_id = $selected_options['board_id'] ? sanitize_text_field($selected_options['board_id']) : '';
        $assignee = $selected_options['assignee'] ? sanitize_text_field($selected_options['assignee']) : '';
        if (! class_exists('FluentBoards\App\Services\BoardService')) {
            return [
                'status' => 'error',
                'message' => __('FluentBoards\App\Services\BoardService class not found.', 'dollie'),

            ];
        }
        if (! class_exists('FluentBoards\App\Models\Board')) {
            return [
                'status' => 'error',
                'message' => __('FluentBoards\App\Models\Board class not found.', 'dollie'),

            ];
        }

        $board = \FluentBoards\App\Models\Board::find($board_id);

        if (! $board) {
            throw new Exception(__('Board not found.', 'dollie'));
        }
        $board_service = new BoardService();
        $member = $board_service->addMembersInBoard(
            $board_id,
            $assignee
        );

        return [
            'board' => $board,
            'member' => $member,
        ];
    }
}

AddUserToBoard::get_instance();
