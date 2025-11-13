<?php

namespace Dollie\SDK\Integrations\Tutorlms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'tutor_after_enrolled',
    label: 'User Enrolled In Course',
    since: '1.0.0'
)]
/**
 * UserEnrolledCourse.
 * php version 5.6
 *
 * @category UserEnrolledCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserEnrolledCourse
 *
 * @category UserEnrolledCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserEnrolledCourse
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'TutorLMS';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'tutor_after_enrolled';

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
     *
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('User Enrolled In Course', 'dollie'),
            'action' => $this->trigger,
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener.
     *
     * @param int  $course_id CourserID.
     * @param int  $user_id UserID.
     * @param bool $is_enrolled Enrollment ID.
     *
     * @return void
     */
    public function trigger_listener($course_id, $user_id, $is_enrolled)
    {
        $context = array_merge(
            WordPress::get_user_context($user_id),
            WordPress::get_post_context($course_id)
        );
        $context['tutor_course'] = $course_id;
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserEnrolledCourse::get_instance();
