<?php

namespace Dollie\SDK\Integrations\Learndash\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_assignment_ld_graded',
    label: 'User Submits Essay Quiz',
    since: '1.0.0'
)]
/**
 * UserAssignmentGraded.
 * php version 5.6
 *
 * @category UserAssignmentGraded
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserAssignmentGraded
 *
 * @category UserAssignmentGraded
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserAssignmentGraded
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
    public $trigger = 'user_assignment_ld_graded';

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
            'action' => 'user_assignment_ld_graded',
            'common_action' => 'learndash_assignment_approved',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int $assignment_id  Assignment ID.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($assignment_id)
    {
        if (empty($assignment_id)) {
            return;
        }

        $assignments = WordPress::get_post_context($assignment_id);

        $context = WordPress::get_user_context((int) $assignments['post_author']);
        $context['sfwd_assignment_id'] = $assignment_id;
        $context['assignment_title'] = get_the_title($assignment_id);
        $context['assignment_url'] = get_post_meta($assignment_id, 'file_link', true);
        $context['sfwd_lesson_topic_id'] = get_post_meta($assignment_id, 'lesson_id', true);
        $context['sfwd-courses'] = get_post_meta($assignment_id, 'course_id', true);
        $context['points'] = get_post_meta($assignment_id, 'points', true);

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserAssignmentGraded::get_instance();
