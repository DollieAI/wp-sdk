<?php

namespace Dollie\SDK\Integrations\Masteriyo\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ma_lms_course_completed',
    label: 'Course Completed',
    since: '1.0.0'
)]
/**
 * MaCourseCompleted.
 * php version 5.6
 *
 * @category MaCourseCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * MaCourseCompleted
 *
 * @category MaCourseCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class MaCourseCompleted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Masteriyo';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ma_lms_course_completed';

    /**
     * Constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register action.
     *
     * @param array $triggers trigger data.
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Course Completed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'masteriyo_course_progress_status_changed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $course_id Course ID.
     * @param string $old_status Old status.
     * @param string $new_status New status.
     * @param object $course_progress The course progress object.
     * @return void
     */
    public function trigger_listener($course_id, $old_status, $new_status, $course_progress)
    {
        if (! function_exists('masteriyo_get_course')) {
            return;
        }
        if ('completed' != $new_status) {
            return;
        }
        if (method_exists($course_progress, 'get_course_id') && method_exists($course_progress, 'get_user_id')) {
            $course = masteriyo_get_course($course_progress->get_course_id());
            $context = array_merge(
                WordPress::get_user_context($course_progress->get_user_id()),
                $course->get_data()
            );

            $context['course_id'] = $course_progress->get_course_id();
            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
MaCourseCompleted::get_instance();
