<?php

namespace Dollie\SDK\Integrations\ServicesForSureCart\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ss_message_final_delivery_sent',
    label: 'Final Delivery Sent',
    since: '1.0.0'
)]
/**
 * MessageFinalDeliverySent.
 * php version 5.6
 *
 * @category MessageFinalDeliverySent
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * MessageFinalDeliverySent
 *
 * @category MessageFinalDeliverySent
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class MessageFinalDeliverySent
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ServicesForSureCart';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ss_message_final_delivery_sent';

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
            'label' => __('Final Delivery Sent', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'surelywp_services_final_delivery_send',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $message_data Message Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($message_data)
    {

        $message_data_arr = [
            'sender' => WordPress::get_user_context($message_data['sender_id']),
            'receiver' => WordPress::get_user_context($message_data['receiver_id']),
            'service_id' => $message_data['service_id'],
            'message_text' => $message_data['message_text'],
            'attachment_file_name' => $message_data['attachment_file_name'],
            'is_final_delivery' => $message_data['is_final_delivery'],
        ];
        $context = $message_data_arr;
        $upload_dir = wp_upload_dir();
        $attachment_file_names = json_decode($message_data['attachment_file_name'], true);
        foreach ((array) $attachment_file_names as $attachment_file_name) {
            $context['attachment_file'][] = $upload_dir['baseurl'] . '/surelywp-services-data/' . $message_data['service_id'] . '/messages/' . $attachment_file_name;
        }
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
MessageFinalDeliverySent::get_instance();
