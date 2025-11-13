<?php

namespace Dollie\SDK\Integrations\PowerfulDocs\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'pfd_feedback_received',
    label: 'Feedback Received',
    since: '1.0.0'
)]
/**
 * PfdFeedbackReceived.
 * php version 5.6
 *
 * @category PfdFeedbackReceived
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PfdFeedbackReceived
 *
 * @category PfdFeedbackReceived
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class PfdFeedbackReceived
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'PowerfulDocs';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'pfd_feedback_received';

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
            'label' => __('Feedback Received', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'pfd_feedback_submitted',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $data Data.
     * @return void
     */
    public function trigger_listener($data)
    {

        if (empty($data)) {
            return;
        }

        $context = $data;
        $user_id = ap_get_current_user_id();
        if ('' != $user_id && is_int($user_id)) {
            $context = array_merge(WordPress::get_user_context(intval($user_id)), $context);
        }
        $context['doc_name'] = get_the_title($data['doc_id']);
        $context['doc_link'] = get_the_permalink($data['doc_id']);
        $context['time'] = wp_date('Y-m-d H:i:s');
        $author_id = get_post_field('post_author', $data['doc_id']);
        $email = get_the_author_meta('email', intval($author_id));
        $context['doc_author_email'] = $email;
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
PfdFeedbackReceived::get_instance();
