<?php

namespace Dollie\SDK\Integrations\MemberPressCourse\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'mpc_course_completed',
    label: 'Course Completed',
    since: '1.0.0'
)]
/**
 * CourseCompleted.
 * php version 5.6
 *
 * @category CompleteCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CourseCompleted
 *
 * @category CourseCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CourseCompleted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'MemberPressCourse';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'mpc_course_completed';

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
            'common_action' => 'mpcs_completed_course',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $data data.
     *
     * @return void
     */
    public function trigger_listener($data)
    {
        if (! isset($data->course_id)) {
            return;
        } else {
            $course_id = $data->course_id;
        }

        if (! isset($data->user_id)) {
            return;
        } else {
            $user_id = $data->user_id;
        }

        $context = array_merge(
            WordPress::get_user_context($user_id),
            [
                'course_id' => $course_id,
                'course_title' => get_the_title($course_id),
                'course_url' => get_permalink($course_id),
                'course_featured_image_id' => get_post_meta($course_id, '_thumbnail_id', true),
                'course_featured_image_url' => get_the_post_thumbnail_url($course_id),
            ]
        );
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $user_id,
                'context' => $context,
            ]
        );
    }
}

CourseCompleted::get_instance();
