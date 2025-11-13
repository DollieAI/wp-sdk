<?php

namespace Dollie\SDK\Integrations\WoocommerceBookings\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use WC_Booking;

#[Trigger(
    id: 'wc_booking_status_changed',
    label: 'Booking Created',
    since: '1.0.0'
)]
/**
 * BookingStatusChanged.
 * php version 5.6
 *
 * @category BookingStatusChanged
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * BookingStatusChanged
 *
 * @category BookingStatusChanged
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class BookingStatusChanged
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WoocommerceBookings';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wc_booking_status_changed';

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
            'common_action' => 'woocommerce_booking_status_changed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;
    }

    /**
     *  Trigger listener
     *
     * @param string $from       Previous status.
     * @param string $to         New (current) status.
     * @param int    $booking_id Booking id.
     * @param object $booking    Booking object.
     *
     * @return void
     */
    public function trigger_listener($from, $to, $booking_id, $booking)
    {

        if ('' == $booking_id) {
            return;
        }

        $was_in_cart = 'was-in-cart';
        if ($to === $was_in_cart || $from === $was_in_cart) {
            return;
        }

        if (class_exists('WC_Booking')) {
            $booking = new WC_Booking($booking_id);
            $person_counts = $booking->get_person_counts();
            $bookable_product_id = $booking->get_product_id();
            if (method_exists($booking, 'get_data')) {
                $booking = $booking->get_data();
                $booking['start'] = gmdate('Y-m-d H:i:s', $booking['start']);
                $booking['end'] = gmdate('Y-m-d H:i:s', $booking['end']);
                if (! empty($person_counts)) {
                    $total_count = 0;
                    foreach ($person_counts as $key => $value) {
                        $total_count += $value;
                    }
                    $booking['total_person_counts'] = $total_count;
                }
                $booking['bookable_product'] = $bookable_product_id;
                $context = array_merge($booking, WordPress::get_user_context($booking['customer_id']));
                $context['from_status'] = $from;
                $context['to_status'] = $to;

                AutomationController::dollie_trigger_handle_trigger(
                    [
                        'trigger' => $this->trigger,
                        'context' => $context,
                    ]
                );
            }
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
BookingStatusChanged::get_instance();
