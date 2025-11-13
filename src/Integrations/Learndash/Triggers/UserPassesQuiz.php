<?php

namespace Dollie\SDK\Integrations\Learndash\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\LearnDash\LearnDash;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_passes_ld_quiz',
    label: 'User Passes Quiz',
    since: '1.0.0'
)]
/**
 * UserPassesLDQuiz.
 * php version 5.6
 *
 * @category UserPassesLDQuiz
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserPassesLDQuiz
 *
 * @category UserPassesLDQuiz
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserPassesLDQuiz
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
    public $trigger = 'user_passes_ld_quiz';

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
            'label' => __('User Passes Quiz', 'dollie'),
            'action' => 'user_passes_ld_quiz',
            'common_action' => ['learndash_quiz_submitted', 'learndash_essay_quiz_data_updated'],
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array  $data quiz data.
     * @param object $current_user current user.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($data, $current_user)
    {

        if (empty($data)) {
            return;
        }

        $passed = ! empty((int) $data['pass']);
        if (! $passed) {
            return;
        }

        // Check if grading is enabled.
        $has_graded = isset($data['has_graded']) ? absint($data['has_graded']) : 0;
        $has_graded = ! empty($has_graded);
        $graded = $has_graded && isset($data['graded']) ? $data['graded'] : false;

        if ($has_graded) {
            if (! empty($graded)) {
                foreach ($graded as $grade_item) {
                    // Quiz has not been graded yet.
                    if (isset($grade_item['status']) && 'not_graded' === $grade_item['status']) {
                        return;
                    }
                }
            }
        }

        $output_questions = LearnDash::get_quiz_questions_answers($data['quiz']);
        if (property_exists($current_user, 'ID')) {
            $current_user = $current_user->ID;
        }
        $context = array_merge(
            WordPress::get_user_context($current_user->ID),
            $output_questions,
            $data
        );

        $context['quiz_name'] = get_the_title($data['quiz']);
        $context['sfwd_quiz_id'] = $data['quiz'];

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserPassesLDQuiz::get_instance();
