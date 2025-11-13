<?php

namespace Dollie\SDK\Integrations\Tutorlms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'tutor_course_complete_after',
    label: 'User complete Course',
    since: '1.0.0'
)]
/**
 * CompleteCourse.
 * php version 5.6
 *
 * @category CompleteCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CompleteCourse
 *
 * @category CompleteCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CompleteCourse
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
    public $trigger = 'tutor_course_complete_after';

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
            'label' => __('User complete Course', 'dollie'),
            'action' => $this->trigger,
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener.
     *
     * @param int $course_id CourserID.
     * @param int $user_id User ID.
     *
     * @return void
     */
    public function trigger_listener($course_id, $user_id)
    {
        $course = get_post($course_id);

        $context = WordPress::get_user_context($user_id);
        $context['tutor_course'] = $course_id;
        if ($course instanceof \WP_Post) {
            $context['course_title'] = $course->post_title;
        }

        $context['course_material_included'] = get_post_meta($course_id, '_tutor_course_material_includes', true) ? get_post_meta($course_id, '_tutor_course_material_includes', true) : '';
        $context['course_reqs'] = get_post_meta($course_id, '_tutor_course_requirements', true) ? get_post_meta($course_id, '_tutor_course_requirements', true) : '';
        $context['course_benefits'] = get_post_meta($course_id, '_tutor_course_benefits', true) ? get_post_meta($course_id, '_tutor_course_benefits', true) : '';
        $context['course_audience'] = get_post_meta($course_id, '_tutor_course_target_audience', true) ? get_post_meta($course_id, '_tutor_course_target_audience', true) : '';
        $context['course_level'] = get_post_meta($course_id, '_tutor_course_level', true) ? get_post_meta($course_id, '_tutor_course_level', true) : '';
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

CompleteCourse::get_instance();
