<?php

namespace Dollie\SDK\Integrations\WPTravelEngine\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'wte_update_booking_status',
    label: 'Update Booking Status',
    since: '1.0.0'
)]
/**
 * UpdateBookingStatus.
 * php version 5.6
 *
 * @category UpdateBookingStatus
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UpdateBookingStatus
 *
 * @category UpdateBookingStatus
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UpdateBookingStatus extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPTravelEngine';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'wte_update_booking_status';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Update Booking Status', 'dollie'),
            'action' => $this->action,
            'function' => [$this, '_action_listener'],
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
     * @return array
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! function_exists('wp_travel_engine_get_booking_status')) {
            return [
                'status' => 'error',
                'message' => __('WP Travel Engine plugin is not active.', 'dollie'),

            ];
        }

        if (empty($selected_options['booking_id'])) {
            return [
                'status' => 'error',
                'message' => __('Booking ID is required.', 'dollie'),

            ];
        }

        if (empty($selected_options['new_status'])) {
            return [
                'status' => 'error',
                'message' => __('New status is required.', 'dollie'),

            ];
        }

        $booking_id = absint($selected_options['booking_id']);
        $new_status = sanitize_text_field($selected_options['new_status']);

        $booking = get_post($booking_id);
        if (! $booking || 'booking' !== $booking->post_type) {
            return [
                'status' => 'error',
                'message' => __('Booking not found.', 'dollie'),

            ];
        }

        $old_status = get_post_meta($booking_id, 'wp_travel_engine_booking_status', true);

        update_post_meta($booking_id, 'wp_travel_engine_booking_status', $new_status);

        do_action('wte_booking_status_changed', $booking_id, $old_status, $new_status);

        return [
            'status' => 'success',
            'data' => [
                'booking_id' => $booking_id,
                'old_status' => $old_status,
                'new_status' => $new_status,
            ],
        ];
    }
}

UpdateBookingStatus::get_instance();
