<?php

namespace Dollie\SDK\Integrations\FluentBooking\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'fluent_booking_appointment_completed',
    label: 'Appointment Completed',
    since: '1.0.0'
)]
/**
 * AppointmentCompleted.
 * php version 5.6
 *
 * @category AppointmentCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AppointmentCompleted
 *
 * @category AppointmentCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AppointmentCompleted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentBooking';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'fluent_booking_appointment_completed';

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
            'label' => __('Appointment Completed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'fluent_booking/booking_schedule_completed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $booking Appointment Data.
     * @param array $calendar_event calendar event Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($booking, $calendar_event)
    {

        if (empty($booking)) {
            return;
        }
        if ('completed' !== $booking['status']) {
            return;
        }
        $booking_data = $booking;
        if (is_object($booking) && method_exists($booking, 'getCustomFormData')) {
            $booking_data['custom_fields'] = $booking->getCustomFormData(false);
        }
        $booking_array = [
            'event_id' => $calendar_event['id'],
            'booking' => $booking_data,
            'event' => $calendar_event,
        ];

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $booking_array,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
AppointmentCompleted::get_instance();
