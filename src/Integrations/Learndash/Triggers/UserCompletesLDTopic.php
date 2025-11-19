<?php

namespace Dollie\SDK\Integrations\Learndash\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\LearnDash\LearnDash;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_completes_ld_topic',
    label: 'Topic Completed',
    since: '1.0.0'
)]
/**
 * UserCompletesLDTopic.
 * php version 5.6
 *
 * @category UserCompletesLDTopic
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserCompletesLDTopic
 *
 * @category UserCompletesLDTopic
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserCompletesLDTopic
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
    public $trigger = 'user_completes_ld_topic';

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
            'label' => __('Topic Completed', 'dollie'),
            'action' => 'user_completes_ld_topic',
            'common_action' => 'learndash_topic_completed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $data course data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($data)
    {
        if (empty($data)) {
            return;
        }

        $context = array_merge(
            WordPress::get_user_context($data['user']->ID),
            LearnDash::get_course_context($data['course']),
            LearnDash::get_lesson_context($data['lesson']),
            LearnDash::get_topic_context($data['topic'])
        );

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserCompletesLDTopic::get_instance();
