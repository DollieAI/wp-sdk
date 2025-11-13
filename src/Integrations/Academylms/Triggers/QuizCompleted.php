<?php

namespace Dollie\SDK\Integrations\Academylms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ac_lms_quiz_completed',
    label: 'Quiz Completed',
    since: '1.0.0'
)]
/**
 * QuizCompleted.
 * php version 5.6
 *
 * @category QuizCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * QuizCompleted
 *
 * @category QuizCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class QuizCompleted
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
    public $trigger = 'ac_lms_quiz_completed';

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
            'label' => __('Quiz Completed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'academy/frontend/after_mark_topic_complete',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param string $topic_type Topic type.
     * @param int    $course_id Attempted Course ID.
     * @param int    $topic_id Topic ID.
     * @param int    $user_id   User ID.
     * @return void
     */
    public function trigger_listener($topic_type, $course_id, $topic_id, $user_id)
    {

        global $wpdb;

        if (empty($topic_id)) {
            return;
        }

        if ('quiz' !== $topic_type) {
            return;
        }

        $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}academy_quiz_attempts WHERE quiz_id=%s AND attempt_status='passed' order by attempt_id DESC LIMIT 1", $topic_id));
        if (! empty($result) && 'passed' === $result[0]->attempt_status) {
            $context = WordPress::get_user_context($user_id);
            $context['quiz_data'] = WordPress::get_post_context($result[0]->quiz_id);
            $context['quiz_attempt_details'] = $result;
            $context['quiz'] = $result[0]->quiz_id;

            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'wp_user_id' => $user_id,
                    'context' => $context,
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
QuizCompleted::get_instance();
