<?php

namespace Dollie\SDK\Integrations\FluentBoards\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'fbs_create_board',
    label: 'Create Board',
    since: '1.0.0'
)]
/**
 * CreateBoard.
 * php version 5.6
 *
 * @category CreateBoard
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateBoard
 *
 * @category CreateBoard
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateBoard extends AutomateAction
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
    public $action = 'fbs_create_board';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Create Board', 'dollie'),
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
        $title = $selected_options['title'] ? sanitize_text_field($selected_options['title']) : '';
        $description = $selected_options['description'] ? sanitize_text_field($selected_options['description']) : '';
        $created_by = $selected_options['created_by'] ? sanitize_text_field($selected_options['created_by']) : '';
        $board_data = array_filter(
            [
                'title' => $title,
                'description' => $description,
                'created_by' => $created_by,
            ],
            fn ($value) => '' !== $value
        );
        if (! function_exists('FluentBoardsApi')) {
            return [
                'status' => 'error',
                'message' => __('FluentBoardsApi function not found.', 'dollie'),

            ];
        }
        $board = FluentBoardsApi('boards')->create($board_data);
        if (empty($board)) {
            return [
                'status' => 'error',
                'message' => 'There is error while creating a Board.',
            ];
        }

        return $board;
    }
}

CreateBoard::get_instance();
