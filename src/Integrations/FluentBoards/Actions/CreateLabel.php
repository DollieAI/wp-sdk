<?php

namespace Dollie\SDK\Integrations\FluentBoards\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentBoards\App\Services\LabelService;

#[Action(
    id: 'fbs_create_label',
    label: 'Create Label',
    since: '1.0.0'
)]
/**
 * CreateLabel.
 * php version 5.6
 *
 * @category CreateLabel
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateLabel
 *
 * @category CreateLabel
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateLabel extends AutomateAction
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
    public $action = 'fbs_create_label';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Create Label', 'dollie'),
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
        $title = ! empty($selected_options['title']) ? sanitize_text_field($selected_options['title']) : '';
        $board_id = ! empty($selected_options['board_id']) ? sanitize_text_field($selected_options['board_id']) : '';
        $color = ! empty($selected_options['color']) ? sanitize_text_field($selected_options['color']) : '';
        $bg_color = ! empty($selected_options['bg-color']) ? sanitize_text_field($selected_options['bg-color']) : '';

        if (! class_exists('FluentBoards\App\Services\LabelService')) {
            return [
                'status' => 'error',
                'message' => __('FluentBoards\App\Services\LabelService class not found.', 'dollie'),

            ];
        }

        $label_data = array_filter(
            [
                'label' => $title,
                'board_id' => $board_id,
                'color' => $color,
                'bg_color' => $bg_color,
            ],
            fn ($value) => '' !== $value
        );

        $label_service = new LabelService();
        $label = $label_service->createLabel($label_data, $board_id);

        if (empty($label)) {
            return [
                'status' => 'error',
                'message' => 'There was an error while creating the label.',
            ];
        }

        return $label;
    }
}

CreateLabel::get_instance();
