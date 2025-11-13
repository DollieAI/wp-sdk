<?php

namespace Dollie\SDK\Integrations\FluentBoards\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'fbs_get_board_details',
    label: 'Get Board',
    since: '1.0.0'
)]
/**
 * GetBoardDetails.
 * php version 5.6
 *
 * @category GetBoardDetails
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GetBoardDetails
 *
 * @category GetBoardDetails
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetBoardDetails extends AutomateAction
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
    public $action = 'fbs_get_board_details';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Get Board', 'dollie'),
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

        // Check if FluentBoardsApi function exists, if not, return early.
        if (! class_exists('FluentBoards\App\Models\Board')) {
            return [
                'status' => 'error',
                'message' => __('FluentBoards\App\Models\Board class not found.', 'dollie'),

            ];
        }
        $board = \FluentBoards\App\Models\Board::find($board_id);
        if (empty($board)) {
            return [
                'status' => 'error',
                'message' => 'There is error while getting board details.',
            ];
        }

        return $board;
    }
}

GetBoardDetails::get_instance();
