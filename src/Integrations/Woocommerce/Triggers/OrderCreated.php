<?php

namespace Dollie\SDK\Integrations\Woocommerce\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wc_order_created',
    label: 'Order created',
    since: '1.0.0'
)]
/**
 * OrderCreated.
 * php version 5.6
 *
 * @category OrderCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * OrderCreated
 *
 * @category OrderCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class OrderCreated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WooCommerce';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wc_order_created';

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
            'label' => __('Order created', 'dollie'),
            'action' => $this->trigger,
            'common_action' => ['woocommerce_new_order'],
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     *  Trigger listener
     *
     * @param int $order_id order ID.
     *
     * @return void
     */
    public function trigger_listener($order_id)
    {
        $order = wc_get_order($order_id);

        if (! $order || ! $order instanceof \WC_Order) {
            return;
        }

        $user_id = $order->get_customer_id();
        $order_context = WooCommerce::get_order_context($order_id);
        $context = array_merge(
            isset($order_context) ? $order_context : [],
            WordPress::get_user_context($user_id)
        );
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
OrderCreated::get_instance();
