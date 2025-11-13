<?php

namespace Dollie\SDK\Integrations\Learnpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\LearnPress\LearnPress;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'learnpress_course_completed',
    label: 'User completes course',
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
    public $integration = 'LearnPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'learnpress_course_completed';

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
            'label' => __('User completes course', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'learn-press/user-course-finished',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $course_id Course id .
     * @param int   $user_id user id.
     * @param mixed $result result.
     *
     * @return void
     */
    public function trigger_listener($course_id, $user_id, $result)
    {
        if (empty($user_id)) {
            return;
        }
        $context = array_merge(
            WordPress::get_user_context($user_id),
            LearnPress::get_lpc_course_context($course_id)
        );
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $user_id,
                'context' => $context,
            ]
        );
    }
}

CompleteCourse::get_instance();
