<?php

namespace Dollie\SDK\Integrations\MasterStudyLms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'stm_lesson_passed',
    label: 'Lesson Completed',
    since: '1.0.0'
)]
/**
 * LessonPassed.
 * php version 5.6
 *
 * @category LessonPassed
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * LessonPassed
 *
 * @category LessonPassed
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class LessonPassed
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'MasterStudyLms';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'stm_lesson_passed';

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
            'label' => __('Lesson Completed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'stm_lms_lesson_passed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $user_id   User Attempting The Lesson.
     * @param int $lesson_id Attempted Lesson ID.
     * @return void
     */
    public function trigger_listener($user_id, $lesson_id)
    {

        if (empty($user_id)) {
            return;
        }

        if (empty($lesson_id)) {
            return;
        }

        $lesson = get_the_title($lesson_id);
        $lesson_link = get_the_permalink($lesson_id);
        $date_completed = date_i18n('Y-m-d H:i:s');

        $data = [
            'lesson' => $lesson_id,
            'lesson_title' => $lesson,
            'lesson_link' => $lesson_link,
            'date_completed' => $date_completed,
        ];

        $context = array_merge($data, WordPress::get_user_context($user_id));

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'user_id' => $user_id,
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
LessonPassed::get_instance();
