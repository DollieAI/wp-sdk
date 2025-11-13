<?php

namespace Dollie\SDK\Integrations\Learndash\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_submits_essay_ld_quiz',
    label: 'User Submits Essay Quiz',
    since: '1.0.0'
)]
/**
 * UserSubmitsEssayLDQuiz.
 * php version 5.6
 *
 * @category UserSubmitsEssayLDQuiz
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserSubmitsEssayLDQuiz
 *
 * @category UserSubmitsEssayLDQuiz
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserSubmitsEssayLDQuiz
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
    public $trigger = 'user_submits_essay_ld_quiz';

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
            'label' => __('User Submits Essay Quiz', 'dollie'),
            'action' => 'user_submits_essay_ld_quiz',
            'common_action' => 'learndash_new_essay_submitted',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int   $essay_id Essay ID.
     * @param array $essay_args Essay Args.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($essay_id, $essay_args)
    {

        if (0 === (int) $essay_id || empty($essay_args)) {
            return;
        }

        $question_post_id = get_post_meta($essay_id, 'question_post_id', true);
        $quiz_post_id = get_post_meta($essay_id, 'quiz_post_id', true);
        $course_id = get_post_meta($essay_id, 'course_id', true);
        $lesson_id = get_post_meta($essay_id, 'lesson_id', true);

        $context = WordPress::get_user_context($essay_args['post_author']);

        $context['quiz_name'] = is_int($quiz_post_id) ? (int) get_the_title($quiz_post_id) : null;
        $context['sfwd_quiz_id'] = $quiz_post_id;
        $context['question_name'] = is_int($question_post_id) ? (int) get_the_title($question_post_id) : null;
        $context['sfwd_question_id'] = $question_post_id;
        $context['course_name'] = is_int($course_id) ? (int) get_the_title($course_id) : null;
        $context['course_id'] = $course_id;
        $context['lesson_name'] = is_int($lesson_id) ? (int) get_the_title($lesson_id) : null;
        $context['lesson_id'] = $lesson_id;
        $context['essay_id'] = $essay_id;
        $context['essay'] = WordPress::get_post_context($essay_id);

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserSubmitsEssayLDQuiz::get_instance();
