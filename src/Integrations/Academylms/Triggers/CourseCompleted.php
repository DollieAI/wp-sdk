<?php

namespace Dollie\SDK\Integrations\Academylms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ac_lms_course_completed',
    label: 'Course Completed',
    since: '1.0.0'
)]
/**
 * CourseCompleted.
 * php version 5.6
 *
 * @category CourseCompleted
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
 *
 * @psalm-suppress UndefinedTrait
 */
class CourseCompleted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'AcademyLMS';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ac_lms_course_completed';

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
            'common_action' => 'academy/admin/course_complete_after',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $course_id Attempted Course ID.
     * @param int $user_id   User ID.
     * @return void
     */
    public function trigger_listener($course_id, $user_id)
    {

        if (empty($user_id)) {
            return;
        }

        $data = WordPress::get_post_context($course_id);
        $context = WordPress::get_user_context($user_id);
        $context['course_data'] = $data;
        $context['course'] = $course_id;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $user_id,
                'context' => $context,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
CourseCompleted::get_instance();
