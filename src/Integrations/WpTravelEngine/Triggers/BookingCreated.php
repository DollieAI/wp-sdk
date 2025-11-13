<?php

namespace Dollie\SDK\Integrations\WPTravelEngine\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wte_booking_created',
    label: 'Booking Created',
    since: '1.0.0'
)]
/**
 * BookingCreated.
 * php version 5.6
 *
 * @category BookingCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * BookingCreated
 *
 * @category BookingCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class BookingCreated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPTravelEngine';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wte_booking_created';

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
            'label' => __('Booking Created', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wp_travel_engine_after_booking_process_completed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $booking_id Booking ID.
     * @return void
     */
    public function trigger_listener($booking_id)
    {
        if (empty($booking_id)) {
            return;
        }

        $booking_data = get_post($booking_id);
        $booking_meta = get_post_meta($booking_id, 'wp_travel_engine_booking_setting', true);

        $payment_status = get_post_meta($booking_id, 'wp_travel_engine_booking_payment_status', true);
        $payment_gateway = get_post_meta($booking_id, 'wp_travel_engine_booking_payment_gateway', true);
        $payment_details = get_post_meta($booking_id, 'wp_travel_engine_booking_payment_details', true);

        $context = [
            'booking_data' => $booking_data,
            'booking_meta' => $booking_meta,
            'payment_status' => $payment_status,
            'payment_gateway' => $payment_gateway,
            'payment_details' => $payment_details,
        ];
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
BookingCreated::get_instance();
