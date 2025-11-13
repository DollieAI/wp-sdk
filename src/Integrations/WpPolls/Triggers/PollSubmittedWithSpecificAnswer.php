<?php

namespace Dollie\SDK\Integrations\WpPolls\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WpPolls\WpPolls;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wp_polls_poll_submitted_with_specific_answer',
    label: 'Poll Submitted with a Specific Answer',
    since: '1.0.0'
)]
/**
 * PollSubmittedWithSpecificAnswer.
 * php version 5.6
 *
 * @category PollSubmittedWithSpecificAnswer
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PollSubmittedWithSpecificAnswer
 *
 * @category PollSubmittedWithSpecificAnswer
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class PollSubmittedWithSpecificAnswer
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WpPolls';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wp_polls_poll_submitted_with_specific_answer';

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
            'label' => __('Poll Submitted with a Specific Answer', 'dollie'),
            'action' => 'wp_polls_poll_submitted_with_specific_answer',
            'common_action' => 'wp_polls_vote_poll_success',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 0,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @since 1.0.0
     * @return void
     */
    public function trigger_listener()
    {
        if (! isset($_POST['poll_id']) || ! isset($_POST['poll_' . $_POST['poll_id']])) {
            return;
        }

        $poll_id = (int) sanitize_key($_POST['poll_id']);

        if (! check_ajax_referer('poll_' . $poll_id . '-nonce', 'poll_' . $poll_id . '_nonce', false)) {
            return;
        }

        $selected_answers_ids_str = sanitize_text_field($_POST['poll_' . $_POST['poll_id']]);
        $selected_answers_ids = explode(',', $selected_answers_ids_str);

        foreach ($selected_answers_ids as $selected_answer_id) {
            $context = WpPolls::get_poll_context($selected_answers_ids_str, $poll_id);
            $context['selected_answer_id'] = $selected_answer_id;

            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
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
PollSubmittedWithSpecificAnswer::get_instance();
