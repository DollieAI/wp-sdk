<?php

namespace Dollie\SDK\Integrations\ServicesForSureCart\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ss_customer_approves_final_delivery',
    label: 'Customer Approves Final Delivery',
    since: '1.0.0'
)]
/**
 * CustomerApprovesFinalDelivery.
 * php version 5.6
 *
 * @category CustomerApprovesFinalDelivery
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CustomerApprovesFinalDelivery
 *
 * @category CustomerApprovesFinalDelivery
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class CustomerApprovesFinalDelivery
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
    public $trigger = 'ss_customer_approves_final_delivery';

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
            'label' => __('Customer Approves Final Delivery', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'surelywp_services_customer_approve_delivery',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int $service_id Service ID.
     * @param int $message_id Message ID.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($service_id, $message_id)
    {
        global $wpdb;

        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}surelywp_sv_messages WHERE service_id = %d AND message_id = %d", $service_id, $message_id), ARRAY_A);
        $user_data = WordPress::get_user_context($result['user_id']);
        unset($result['user_id']);
        $context = array_merge($result, $user_data);
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
CustomerApprovesFinalDelivery::get_instance();
