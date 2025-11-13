<?php

namespace Dollie\SDK\Integrations\Affiliatewp\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'affiliate_wc_product_purchased',
    label: 'WooCommerce Product Purchased using Affiliate Referral',
    since: '1.0.0'
)]
/**
 * AffiliateWcProductPurchased.
 * php version 5.6
 *
 * @category AffiliateWcProductPurchased
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AffiliateWcProductPurchased
 *
 * @category AffiliateWcProductPurchased
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AffiliateWcProductPurchased
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'AffiliateWP';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'affiliate_wc_product_purchased';

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
     *
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('WooCommerce Product Purchased using Affiliate Referral', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'affwp_insert_referral',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $referral_id Referral ID.
     *
     * @return void|array
     */
    public function trigger_listener($referral_id)
    {

        if (! function_exists('affwp_get_referral') || ! function_exists('affwp_get_affiliate') || ! function_exists('affwp_get_dynamic_affiliate_coupons')) {
            return;
        }

        $referral = affwp_get_referral($referral_id);

        if ('woocommerce' !== (string) $referral->context) {
            return;
        }

        $order_id = $referral->reference;
        $order = wc_get_order($order_id);
        if (! $order instanceof \WC_Order) {
            return;
        }
        $user_id = $order->get_customer_id();

        $referral = affwp_get_referral($referral->referral_id);
        $affiliate = affwp_get_affiliate($referral->affiliate_id);
        $affiliate_data = get_object_vars($affiliate);
        $user_data = WordPress::get_user_context($user_id);
        $referral_data = get_object_vars($referral);
        $dynamic_coupons = affwp_get_dynamic_affiliate_coupons($referral->affiliate_id, false);

        if (function_exists('affwp_calc_referral_amount') && function_exists('affiliate_wp')) {
            $referral_data['amount'] = number_format(affwp_calc_referral_amount($order->get_total(), $affiliate->ID), 2);
            $referral_data['products'] = [];
            $items = $order->get_items();
            foreach ($items as $item) {
                if (! affiliate_wp()->settings->get('exclude_tax')) {
                    $amount = $item['line_total'] + $item['line_tax'];
                } else {
                    $amount = $item['line_total'];
                }
                $referral_data['products'][] = [
                    'name' => $item['name'],
                    'id' => $item['product_id'],
                    'price' => $amount,
                    'referral_amount' => number_format(affwp_calc_referral_amount($amount, $order_id, $item['product_id']), 2),
                ];
            }
        }
        $context = array_merge(
            $user_data,
            $affiliate_data,
            $referral_data,
            $dynamic_coupons
        );
        $items = $order->get_items();
        foreach ($items as $item) {
            $context['product'] = $item['product_id'];
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $affiliate->user_id,
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
AffiliateWcProductPurchased::get_instance();
