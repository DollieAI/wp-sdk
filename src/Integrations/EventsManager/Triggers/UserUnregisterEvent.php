<?php

namespace Dollie\SDK\Integrations\EventsManager\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'em_user_unregister_from_event',
    label: 'User Unregister from event',
    since: '1.0.0'
)]
/**
 * UserUnregisterEvent.
 * php version 5.6
 *
 * @category UserRegisterInEvent
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PurchaseMembership
 *
 * @category PurchaseMembership
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserUnregisterEvent
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'EventsManager';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'em_user_unregister_from_event';

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
            'label' => __('User Unregister from event', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'em_booking_status_changed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param object $em_booking_obj Event data.
     * @param array  $em_status Event booking status.
     *
     * @return void
     */
    public function trigger_listener($em_booking_obj, $em_status)
    {
        if (3 !== $em_status['status'] || ! property_exists($em_booking_obj, 'event_id') || ! property_exists($em_booking_obj, 'person_id')) {
            return;
        }

        $event_id = $em_booking_obj->event_id;
        $user_id = $em_booking_obj->person_id;
        global $wpdb;

        $all_bookings = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}em_events as e where e.event_id = %d", $event_id));
        $context = array_merge(
            WordPress::get_user_context($user_id),
            (array) json_decode((string) wp_json_encode($all_bookings), true)
        );
        $context['post_id'] = $all_bookings->post_id;
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
UserUnregisterEvent::get_instance();
