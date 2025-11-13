<?php

namespace Dollie\SDK\Integrations\WoocommerceShipstation\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wc_specific_product_order_shipped',
    label: 'Order For Specific Product Shipped',
    since: '1.0.0'
)]
/**
 * SpecificProductOrderShipped.
 * php version 5.6
 *
 * @category SpecificProductOrderShipped
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SpecificProductOrderShipped
 *
 * @category SpecificProductOrderShipped
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class SpecificProductOrderShipped
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WoocommerceShipstation';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wc_specific_product_order_shipped';

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
            'label' => __('Order For Specific Product Shipped', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'woocommerce_shipstation_shipnotify',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     *  Trigger listener
     *
     * @param object $order Order.
     * @param array  $argu Arg.
     *
     * @return void
     */
    public function trigger_listener($order, $argu)
    {

        if (! $order) {
            return;
        }

        if (method_exists($order, 'get_user_id')) {
            $user_id = $order->get_user_id();
            if (0 === $user_id) {
                return;
            }
            $product_ids = [];
            if (method_exists($order, 'get_id')) {
                $order_id = $order->get_id();
                if (method_exists($order, 'get_items')) {
                    $items = $order->get_items();
                    foreach ($items as $item) {
                        $product_ids[] = $item->get_product_id();
                    }
                }

                $order_detail = WooCommerce::get_order_context($order_id);
                if (is_array($order_detail)) {
                    $context = array_merge(
                        $order_detail,
                        WordPress::get_user_context($user_id)
                    );
                    foreach ($product_ids as $product_id) {
                        $context['product_id'] = $product_id;
                    }

                    $context['shipping_tracking_number'] = $argu['tracking_number'];
                    $context['shipping_carrier'] = $argu['carrier'];
                    $timestamp = strtotime((string) $argu['ship_date']);
                    /**
                     * Ignore line
                     *
                     * @phpstan-ignore-next-line
                     */
                    $date = date_i18n(get_option('date_format'), $timestamp);
                    $context['ship_date'] = $date;

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
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
SpecificProductOrderShipped::get_instance();
