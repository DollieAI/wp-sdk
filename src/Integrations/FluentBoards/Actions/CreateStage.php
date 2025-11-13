<?php

namespace Dollie\SDK\Integrations\FluentBoards\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentBoards\App\Services\StageService;

#[Action(
    id: 'fbs_create_stage',
    label: 'Create Stage',
    since: '1.0.0'
)]
/**
 * CreateStage.
 * php version 5.6
 *
 * @category CreateStage
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateStage
 *
 * @category CreateStage
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateStage extends AutomateAction
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
    public $action = 'fbs_create_stage';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Create Stage', 'dollie'),
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
        $board_id = $selected_options['board_id'] ? sanitize_text_field($selected_options['board_id']) : '';
        $status = $selected_options['status'] ? sanitize_text_field($selected_options['status']) : '';

        if (! class_exists('FluentBoards\App\Services\StageService')) {
            return [
                'status' => 'error',
                'message' => __('FluentBoards\App\Services\StageService class not found.', 'dollie'),

            ];
        }

        $stage_data = array_filter(
            [
                'title' => $title,
                'board_id' => $board_id,
                'status' => $status,
            ],
            fn ($value) => '' !== $value
        );
        $stage_service = new StageService();
        $stage = $stage_service->createStage($stage_data, $board_id);

        if (empty($stage)) {
            return [
                'status' => 'error',
                'message' => 'There is error while creating a Stage.',
            ];
        }

        return $stage;
    }
}

CreateStage::get_instance();
