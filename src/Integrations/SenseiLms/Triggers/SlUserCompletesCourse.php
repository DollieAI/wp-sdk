<?php

namespace Dollie\SDK\Integrations\SenseiLMS\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'sl_user_completes_course',
    label: 'User Completes Course',
    since: '1.0.0'
)]
/**
 * SlUserCompletesCourse.
 * php version 5.6
 *
 * @category SlUserCompletesCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SlUserCompletesCourse
 *
 * @category SlUserCompletesCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class SlUserCompletesCourse
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
    public $trigger = 'sl_user_completes_course';

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
            'label' => __('User Completes Course', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'sensei_user_course_end',
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
     * @param int $course_id The course ID.
     *
     * @return void
     */
    public function trigger_listener($user_id, $course_id)
    {
        $course = get_post($course_id);

        $context = WordPress::get_user_context($user_id);
        $context['sensei_course'] = $course_id;
        if ($course instanceof \WP_Post) {
            $context['course_title'] = $course->post_title;
        }
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

SlUserCompletesCourse::get_instance();
