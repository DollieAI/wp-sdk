<?php

namespace Dollie\SDK\Integrations\Wplms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Integrations\WPLMS\WPLMS;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wplms_submit_course',
    label: 'User complete course',
    since: '1.0.0'
)]
/**
 * WplmsCourseCompleted.
 * php version 5.6
 *
 * @category WplmsCourseCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WplmsCourseCompleted
 *
 * @category WplmsCourseCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WplmsCourseCompleted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPLMS';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wplms_submit_course';

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
            'label' => __('User complete course', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wplms_submit_course',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $course_id Course id.
     * @param int $user_id user id.
     *
     * @return void
     */
    public function trigger_listener($course_id, $user_id)
    {
        if (! $user_id) {
            $user_id = ap_get_current_user_id();
        }
        if (empty($user_id)) {
            return;
        }

        $context = array_merge(
            WordPress::get_user_context($user_id),
            WPLMS::get_wplms_course_context($course_id)
        );

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

WplmsCourseCompleted::get_instance();
