<?php

namespace Dollie\SDK\Integrations\SenseiLMS\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'sl_user_completes_lesson',
    label: 'User Completes Lesson',
    since: '1.0.0'
)]
/**
 * SlUserCompletesLesson.
 * php version 5.6
 *
 * @category SlUserCompletesLesson
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SlUserCompletesLesson
 *
 * @category SlUserCompletesLesson
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class SlUserCompletesLesson
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'SenseiLMS';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'sl_user_completes_lesson';

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
            'label' => __('User Completes Lesson', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'sensei_user_lesson_end',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener.
     *
     * @param int $user_id   The user ID.
     * @param int $lesson_id The lesson ID.
     *
     * @return void
     */
    public function trigger_listener($user_id, $lesson_id)
    {
        $lesson = get_post($lesson_id);

        $context = WordPress::get_user_context($user_id);
        $context['sensei_lesson'] = $lesson_id;
        if ($lesson instanceof \WP_Post) {
            $context['course_title'] = $lesson->post_title;
        }
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

SlUserCompletesLesson::get_instance();
