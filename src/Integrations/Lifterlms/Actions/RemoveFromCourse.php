<?php

namespace Dollie\SDK\Integrations\Lifterlms\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\LifterLMS\LifterLMS;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'lms_remove_from_course',
    label: 'Remove user from course',
    since: '1.0.0'
)]
/**
 * RemoveFromCourse.
 * php version 5.6
 *
 * @category RemoveFromCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RemoveFromCourse
 *
 * @category RemoveFromCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RemoveFromCourse extends AutomateAction
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
    public $action = 'lms_remove_from_course';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Remove user from course', 'dollie'),
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
     *
     * @psalm-suppress InvalidScalarArgument
     * @psalm-suppress UndefinedMethod
     *
     * @return bool|array|object
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        if (! function_exists('llms_unenroll_student')) {
            return [
                'status' => 'error',
                'message' => __('LifterLMS enrollment function not found.', 'dollie'),

            ];
        }
        $course_id = isset($selected_options['course']) ? $selected_options['course'] : '0';
        $course = get_post((int) $course_id);

        if (! $course) {
            return [
                'status' => 'error',
                'message' => __('No course is available ', 'dollie'),

            ];
        }

        llms_unenroll_student($user_id, $course_id);
        $course_data = LifterLMS::get_lms_course_context($course_id);

        return $course_data;
    }
}

RemoveFromCourse::get_instance();
