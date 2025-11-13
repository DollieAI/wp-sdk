<?php

namespace Dollie\SDK\Integrations\Tutorlms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'tlms_quiz_percentage',
    label: 'User passes Quiz',
    since: '1.0.0'
)]
/**
 * UserAchievesPercentage.
 * php version 5.6
 *
 * @category UserAchievesPercentage
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserAchievesPercentage
 *
 * @category UserAchievesPercentage
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserAchievesPercentage
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'TutorLMS';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'tlms_quiz_percentage';

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
            'label' => __('User passes Quiz', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'tutor_quiz/attempt_ended',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener.
     *
     * @param int $attempt_id Attempt ID.
     *
     * @return void
     */
    public function trigger_listener($attempt_id)
    {

        if (! function_exists('tutor_utils')) {
            return;
        }

        $attempt = tutor_utils()->get_attempt($attempt_id);
        if ('tutor_quiz' !== get_post_type($attempt->quiz_id)) {
            return;
        }

        $score = (int) $attempt->earned_marks;

        $context = WordPress::get_user_context($attempt->user_id);
        $context['quiz_id'] = $attempt->quiz_id;
        $context['attempt_id'] = $attempt_id;
        $context['attempt'] = $attempt;
        $context['percentage'] = $score;
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserAchievesPercentage::get_instance();
