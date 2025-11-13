<?php

namespace Dollie\SDK\Integrations\EventsManager\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'em_user_register_in_event',
    label: 'User Registered in Event',
    since: '1.0.0'
)]
/**
 * UserRegisterInEvent.
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
class UserRegisterInEvent
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
    public $trigger = 'em_user_register_in_event';

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
            'label' => __('User Registered in Event', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'em_bookings_added',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param object $em_booking_obj Event data.
     *
     * @return void
     */
    public function trigger_listener($em_booking_obj)
    {
        if (! property_exists($em_booking_obj, 'event_id') || ! property_exists($em_booking_obj, 'person_id')) {
            return;
        }
        $event_id = $em_booking_obj->event_id;
        $user_id = $em_booking_obj->person_id;
        global $wpdb;
        $all_bookings = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}em_bookings as b INNER JOIN {$wpdb->prefix}em_events as e ON b.event_id = e.event_id WHERE e.event_status = 1 AND b.booking_status NOT IN (2,3) AND b.event_id = %s", $event_id));

        $location = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}em_locations as b WHERE b.location_id  = %s", $all_bookings->location_id));
        $context = array_merge(
            WordPress::get_user_context($user_id),
            (array) json_decode((string) wp_json_encode($all_bookings), true)
        );
        if (! empty($location)) {
            $context = array_merge($context, (array) $location);
        }
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
UserRegisterInEvent::get_instance();
