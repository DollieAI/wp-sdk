<?php

namespace Dollie\SDK\Integrations\Masteriyo\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ma_lms_quiz_failed',
    label: 'Quiz Failed',
    since: '1.0.0'
)]
/**
 * MaQuizFailed.
 * php version 5.6
 *
 * @category MaQuizFailed
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * MaQuizFailed
 *
 * @category MaQuizFailed
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class MaQuizFailed
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Masteriyo';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ma_lms_quiz_failed';

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
            'label' => __('Quiz Failed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'masteriyo_quiz_attempt_status_changed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $attempt The course object.
     * @param string $old_status Old status.
     * @param string $new_status New status.
     * @return void
     */
    public function trigger_listener($attempt, $old_status, $new_status)
    {

        if (! function_exists('masteriyo_get_quiz')) {
            return;
        }

        if (is_object($attempt) && method_exists($attempt, 'get_quiz_id') && method_exists($attempt, 'get_course_id')) {
            $quiz_id = $attempt->get_quiz_id();
            $quiz = masteriyo_get_quiz($quiz_id);
            if (is_null($quiz)) {
                return;
            }

            $course_id = $attempt->get_course_id();
            if (method_exists($attempt, 'get_earned_marks') && (is_object($quiz) && method_exists($quiz, 'get_pass_mark') && method_exists($quiz, 'get_data')) && method_exists($attempt, 'get_data') && method_exists($attempt, 'get_user_id')) {
                $failed = $attempt->get_earned_marks() < $quiz->get_pass_mark();
                if (! $failed) {
                    return;
                }
                $context = WordPress::get_user_context($attempt->get_user_id());
                $context['quiz'] = $quiz->get_data();
                $context['attempt'] = $attempt->get_data();
                $context['quiz_id'] = $quiz_id;
                $context['course_id'] = $course_id;

                AutomationController::dollie_trigger_handle_trigger(
                    [
                        'trigger' => $this->trigger,
                        'context' => $context,
                    ]
                );
            }
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
MaQuizFailed::get_instance();
