<?php

namespace Dollie\SDK\Integrations\Lifterlms\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'lms_mark_section_complete',
    label: 'Mark section complete for User',
    since: '1.0.0'
)]
/**
 * MarkSectionComplete.
 * php version 5.6
 *
 * @category MarkSectionComplete
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * MarkSectionComplete
 *
 * @category MarkSectionComplete
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class MarkSectionComplete extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'LifterLMS';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'lms_mark_section_complete';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Mark section complete for User', 'dollie'),
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
     * @return void|bool|array
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        $section_id = $selected_options['section'];
        $user_email = $selected_options['wp_user_email'];

        if (! class_exists('LLMS_Section')) {
            return [
                'status' => 'error',
                'message' => __('LLMS_Section class not found.', 'dollie'),

            ];
        }

        if (is_email($user_email)) {
            $user = get_user_by('email', $user_email);
            if ($user) {
                $user_id = $user->ID;
                if (! function_exists('llms_mark_complete')) {
                    return [
                        'status' => 'error',
                        'message' => __('The function llms_mark_complete does not exist', 'dollie'),

                    ];
                }

                // Get all lessons of section.
                $section = new \LLMS_Section($section_id);
                $lessons = $section->get_lessons();
                if (! empty($lessons)) {
                    foreach ($lessons as $lesson) {
                        llms_mark_complete($user_id, $lesson->id, 'lesson');
                    }
                }

                llms_mark_complete($user_id, $section_id, 'section');

                return array_merge(WordPress::get_post_context($section_id), WordPress::get_user_context($user_id));
            } else {
                return [
                    'status' => 'error',
                    'message' => 'User not exists.',
                ];
            }
        } else {
            return [
                'status' => 'error',
                'message' => 'Enter valid email address.',
            ];
        }
    }
}

MarkSectionComplete::get_instance();
