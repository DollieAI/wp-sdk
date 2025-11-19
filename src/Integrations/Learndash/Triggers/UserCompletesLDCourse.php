<?php

namespace Dollie\SDK\Integrations\Learndash\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\LearnDash\LearnDash;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_completes_ld_course',
    label: 'Course Completed',
    since: '1.0.0'
)]
/**
 * UserCompletesLDCourse.
 * php version 5.6
 *
 * @category UserCompletesLDCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserCompletesLDCourse
 *
 * @category UserCompletesLDCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserCompletesLDCourse
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'LearnDash';

    /**
     * Action name.
     *
     * @var string
     */
    public $trigger = 'user_completes_ld_course';

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
     * Register a action.
     *
     * @param array $triggers actions.
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Course Completed', 'dollie'),
            'action' => 'user_completes_ld_course',
            'common_action' => 'learndash_course_completed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $data course data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($data)
    {
        if (empty($data)) {
            return;
        }

        $context = array_merge(
            WordPress::get_user_context($data['user']->ID),
            LearnDash::get_course_context($data['course'])
        );

        $context['course_status'] = $data['course']->post_status;
        $timestamp = get_user_meta($data['user']->ID, 'course_completed_' . $data['course']->ID, true);
        $timestamp = is_numeric($timestamp) ? (int) $timestamp : null;
        $date_format = get_option('date_format');
        if (is_string($date_format)) {
            $context['course_completion_date'] = wp_date($date_format, $timestamp);
        }
        if (function_exists('learndash_get_course_certificate_link')) {
            $context['course_certificate'] = learndash_get_course_certificate_link($data['course']->ID, $data['user']->ID);
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserCompletesLDCourse::get_instance();
