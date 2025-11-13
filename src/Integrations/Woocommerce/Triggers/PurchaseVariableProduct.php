<?php

namespace Dollie\SDK\Integrations\Woocommerce\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wc_purchase_variable_product',
    label: 'User purchases a variable product with a variation selected',
    since: '1.0.0'
)]
/**
 * PurchaseVariableProduct.
 * php version 5.6
 *
 * @category PurchaseVariableProduct
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PurchaseVariableProduct
 *
 * @category PurchaseVariableProduct
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class PurchaseVariableProduct
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
    public $trigger = 'wc_purchase_variable_product';

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
            'label' => __('User purchases a variable product with a variation selected', 'dollie'),
            'action' => $this->trigger,
            'common_action' => ['woocommerce_checkout_order_processed', 'woocommerce_store_api_checkout_order_processed'],
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
        if (! $order_id) {
            return;
        }

        $order = wc_get_order($order_id);

        if (! $order || ! $order instanceof \WC_Order) {
            return;
        }

        $user_id = $order->get_customer_id();

        $items = $order->get_items();
        $product_variations = [];

        foreach ($items as $item) {
            if ($item instanceof \WC_Order_Item_Product) {
                $product_variations[] = $item->get_variation_id();
            }
        }
        foreach ($product_variations as $product_variation_id) {
            $product_id = wp_get_post_parent_id($product_variation_id);
            if ($product_id) {
                $order_context = WooCommerce::get_order_context($order_id);
                $context = array_merge(
                    WooCommerce::get_product_context($product_id),
                    isset($order_context) ? $order_context : [],
                    WordPress::get_user_context($user_id)
                );
                $context['product_variation_id'] = $product_variation_id;
                $context['product_variation'] = get_the_excerpt($product_variation_id);
                $context['total_items_in_order'] = count($items);

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

PurchaseVariableProduct::get_instance();
