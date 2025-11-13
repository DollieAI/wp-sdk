<?php

namespace Dollie\SDK\Integrations\AppointmentHourBooking\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ahb_booking_status_updated',
    label: 'Booking Status Updated',
    since: '1.0.0'
)]
/**
 * BookingStatusUpdated.
 * php version 5.6
 *
 * @category BookingStatusUpdated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * BookingStatusUpdated
 *
 * @category BookingStatusUpdated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class BookingStatusUpdated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'AppointmentHourBooking';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ahb_booking_status_updated';

    /**
     * Constructor
     *
     * @since 1.0.0
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
            'label' => __('Booking Status Updated', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'cpappb_update_status',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $id Appointment ID.
     * @param string $status Appointment Status.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($id, $status)
    {

        global $wpdb;
        $events = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}cpappbk_messages 
            WHERE id=%d",
                $id
            )
        ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $posted_data = unserialize($events[0]->posted_data);
        $context = $posted_data;

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
BookingStatusUpdated::get_instance();
