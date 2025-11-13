<?php

namespace Dollie\SDK\Integrations\Woocommerce\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wp_view_product',
    label: 'User views a product',
    since: '1.0.0'
)]
/**
 * ViewProduct.
 * php version 5.6
 *
 * @category ViewProduct
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ViewProduct
 *
 * @category ViewProduct
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ViewProduct
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
    public $trigger = 'wp_view_product';

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
            'label' => __('User views a product', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'template_redirect',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @return void
     */
    public function trigger_listener()
    {
        if (! class_exists('WooCommerce')) {
            return;
        }

        if (! is_product()) {
            return;
        }

        $user_id = ap_get_current_user_id();
        $product_id = get_queried_object_id();
        $product_data['product_id'] = $product_id;
        $product_data['product'] = WooCommerce::get_product_context($product_id);
        $terms = get_the_terms($product_id, 'product_cat');
        if (! empty($terms) && is_array($terms) && isset($terms[0])) {
            $cat_name = [];
            foreach ($terms as $cat) {
                $cat_name[] = $cat->name;
            }
            $product_data['product']['category'] = implode(', ', $cat_name);
        }
        $terms_tags = get_the_terms($product_id, 'product_tag');
        if (! empty($terms_tags) && is_array($terms_tags) && isset($terms_tags[0])) {
            $tag_name = [];
            foreach ($terms_tags as $tag) {
                $tag_name[] = $tag->name;
            }
            $product_data['product']['tag'] = implode(', ', $tag_name);
        }
        unset($product_data['product']['id']); //phpcs:ignore

        $context = array_merge(
            $product_data,
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

ViewProduct::get_instance();
