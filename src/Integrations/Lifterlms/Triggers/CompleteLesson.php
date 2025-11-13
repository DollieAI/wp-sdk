<?php

namespace Dollie\SDK\Integrations\Lifterlms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\LifterLMS\LifterLMS;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'lifterlms_lesson_completed',
    label: 'User Completes a Lesson',
    since: '1.0.0'
)]
/**
 * CompleteLesson.
 * php version 5.6
 *
 * @category CompleteLesson
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CompleteLesson
 *
 * @category CompleteLesson
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CompleteLesson
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
    public $trigger = 'lifterlms_lesson_completed';

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
            'label' => __('User Completes a Lesson', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'lifterlms_lesson_completed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener.
     *
     * @param int $user_id id user ID.
     * @param int $lesson_id Lesson ID.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($user_id, $lesson_id)
    {
        if (! $user_id) {
            $user_id = ap_get_current_user_id();
        }
        if (empty($user_id)) {
            return;
        }

        $context = array_merge(
            WordPress::get_user_context($user_id),
            LifterLMS::get_lms_lesson_context($lesson_id)
        );
        $course_id = get_post_meta($lesson_id, '_llms_parent_course', true);

        /**
         * Course ID.
         *
         * @var string $course_id
         */
        $context['course'] = get_the_title(intval($course_id));

        $parent_section_id = get_post_meta($lesson_id, '_llms_parent_section', true);
        if ('' !== $parent_section_id) {
            /**
             * Parent Section ID.
             *
             * @var string $parent_section_id
             */
            $context['parent_section'] = get_the_title(intval($parent_section_id));
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'user_id' => $user_id,
                'context' => $context,
            ]
        );
    }
}

CompleteLesson::get_instance();
