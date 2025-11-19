<?php

namespace Dollie\SDK\Integrations\BetterMessages\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'bm_new_message_received',
    label: 'New Message Received',
    since: '1.0.0'
)]
/**
 * BMNewMessageReceived.
 * php version 5.6
 *
 * @category BMNewMessageReceived
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * BMNewMessageReceived
 *
 * @category BMNewMessageReceived
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class BMNewMessageReceived
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BetterMessages';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'bm_new_message_received';

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
            'label' => __('New Message Received', 'dollie'),
            'action' => 'bm_new_message_received',
            'common_action' => 'better_messages_message_sent',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $message Message object.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($message)
    {
        if (! function_exists('Better_Messages') || ! property_exists($message, 'id')) {
            return;
        }

        $message = Better_Messages()->functions->get_message($message->id);
        if (is_object($message)) {
            $message = get_object_vars($message);
        }
        $context = $message;
        $context['sender'] = WordPress::get_user_context($message->sender_id);
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

BMNewMessageReceived::get_instance();
