<?php

namespace Dollie\SDK\Integrations\Woocommerce\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'woocommerce_add_to_cart',
    label: 'Product is added to cart',
    since: '1.0.0'
)]
/**
 * AddProductToCart.
 * php version 5.6
 *
 * @category AddProductToCart
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddProductToCart
 *
 * @category AddProductToCart
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddProductToCart
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
    public $trigger = 'woocommerce_add_to_cart';

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
            'label' => __('Product is added to cart', 'dollie'),
            'action' => $this->trigger,
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 6,
        ];

        return $triggers;
    }

    /**
     *  Trigger listener
     *
     * @param int   $cart_item_key cart item key.
     * @param int   $product_id product id.
     * @param int   $quantity quantity.
     * @param int   $variation_id variation id.
     * @param int   $variation variation.
     * @param array $cart_item_data cart item data.
     *
     * @return void
     */
    public function trigger_listener($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data)
    {

        $user_id = ap_get_current_user_id();
        $context = WordPress::get_user_context($user_id);
        $context['product_id'] = $product_id;
        $context['product'] = WooCommerce::get_product_context($product_id);
        $terms = get_the_terms($product_id, 'product_cat');
        if (! empty($terms) && is_array($terms) && isset($terms[0])) {
            $cat_name = [];
            foreach ($terms as $cat) {
                $cat_name[] = $cat->name;
            }
            $context['product']['category'] = implode(', ', $cat_name);
        }
        $terms_tags = get_the_terms($product_id, 'product_tag');
        if (! empty($terms_tags) && is_array($terms_tags) && isset($terms_tags[0])) {
            $tag_name = [];
            foreach ($terms_tags as $tag) {
                $tag_name[] = $tag->name;
            }
            $context['product']['tag'] = implode(', ', $tag_name);
        }
        unset($context['product']['id']);

        $context['product_quantity'] = $quantity;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

AddProductToCart::get_instance();
