<?php

namespace Dollie\SDK\Integrations\LatePoint\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use OsBookingModel;

#[Action(
    id: 'lp_cancel_booking',
    label: 'Cancel Booking',
    since: '1.0.0'
)]
/**
 * CancelBooking.
 * php version 5.6
 *
 * @category CancelBooking
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CancelBooking
 *
 * @category CancelBooking
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CancelBooking extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'LatePoint';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'lp_cancel_booking';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Cancel Booking', 'dollie'),
            'action' => 'lp_cancel_booking',
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user_id.
     * @param int   $automation_id automation_id.
     * @param array $fields fields.
     * @param array $selected_options selectedOptions.
     *
     * @throws Exception Exception.
     *
     * @return array
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! class_exists('OsBookingModel')) {
            return [
                'status' => 'error',
                'message' => 'LatePoint plugin not installed.',
            ];
        }

        $booking_id = isset($selected_options['booking_id']) ? $selected_options['booking_id'] : null;
        if (! $booking_id) {
            return [
                'status' => 'error',
                'message' => 'Booking ID not provided.',
            ];
        }

        $booking = new OsBookingModel($booking_id);
        if (! isset($booking->id) || ! $booking->id) {
            return [
                'status' => 'error',
                'message' => 'Booking not found.',
            ];
        }

        if ($booking->update_status('cancelled')) {
            return $booking->get_data_vars();
        } else {
            return [
                'status' => 'error',
                'message' => 'Booking could not be cancelled.',
            ];
        }
    }
}

CancelBooking::get_instance();
