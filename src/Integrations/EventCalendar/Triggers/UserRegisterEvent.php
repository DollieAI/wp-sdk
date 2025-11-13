<?php

namespace Dollie\SDK\Integrations\EventCalendar\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\TheEventCalendar\TheEventCalendar;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_register_for_event',
    label: 'New Attendee',
    since: '1.0.0'
)]
/**
 * UserRegisterEvent.
 * php version 5.6
 *
 * @category UserRegisterEvent
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserRegisterEvent
 *
 * @category UserRegisterEvent
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserRegisterEvent
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'TheEventCalendar';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_register_for_event';

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
            'label' => __('New Attendee', 'dollie'),
            'action' => $this->trigger,
            'common_action' => [
                'event_tickets_rsvp_tickets_generated_for_product',
                'event_tickets_woocommerce_tickets_generated_for_product',
                'event_tickets_tpp_tickets_generated_for_product',
            ],
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $product_id Product ID.
     * @param int $order_id Order ID.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($product_id, $order_id)
    {
        if (empty($order_id)) {
            return;
        }

        $context = TheEventCalendar::get_event_context($product_id, $order_id);

        if (empty($context)) {
            return;
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
UserRegisterEvent::get_instance();
