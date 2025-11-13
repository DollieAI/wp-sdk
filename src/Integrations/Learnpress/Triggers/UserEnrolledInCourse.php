<?php

namespace Dollie\SDK\Integrations\Learnpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\LearnPress\LearnPress;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'learnpress_user_enrolled_in_course',
    label: 'User Enrolled In Course',
    since: '1.0.0'
)]
/**
 * UserEnrolledInCourse.
 * php version 5.6
 *
 * @category UserEnrolledInCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserEnrolledInCourse
 *
 * @category CompleteCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserEnrolledInCourse
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'LearnPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'learnpress_user_enrolled_in_course';

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
            'label' => __('User Enrolled In Course', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'learnpress/user/course-enrolled',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $order_id order id.
     * @param int $course_id Course id.
     * @param int $user_id user id id.
     *
     * @return void
     */
    public function trigger_listener($order_id, $course_id, $user_id)
    {
        if (empty($user_id)) {
            return;
        }
        $context = array_merge(
            WordPress::get_user_context($user_id),
            LearnPress::get_lpc_course_context($course_id)
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

UserEnrolledInCourse::get_instance();
