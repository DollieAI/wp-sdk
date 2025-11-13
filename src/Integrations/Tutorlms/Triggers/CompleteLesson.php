<?php

namespace Dollie\SDK\Integrations\Tutorlms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'tutor_lesson_completed_after',
    label: 'User complete Lesson',
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
    public $integration = 'TutorLMS';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'tutor_lesson_completed_after';

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
            'label' => __('User complete Lesson', 'dollie'),
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
     * @param int $lesson_id CourserID.
     * @param int $user_id User ID.
     *
     * @return void
     */
    public function trigger_listener($lesson_id, $user_id)
    {
        $lesson = get_post($lesson_id);

        $context = WordPress::get_user_context($user_id);
        $context['lesson_id'] = $lesson_id;
        if ($lesson instanceof \WP_Post) {
            $context['lesson_title'] = $lesson->post_title;
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

CompleteLesson::get_instance();
