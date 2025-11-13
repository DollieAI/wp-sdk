<?php

namespace Dollie\SDK\Integrations\Affiliatewp\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use MeprTransaction;

#[Trigger(
    id: 'affiliate_mb_product_purchased',
    label: 'MemberPress Product Purchased using Affiliate Referral',
    since: '1.0.0'
)]
/**
 * AffiliateMbProductPurchased.
 * php version 5.6
 *
 * @category AffiliateMbProductPurchased
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AffiliateMbProductPurchased
 *
 * @category AffiliateMbProductPurchased
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AffiliateMbProductPurchased
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
    public $trigger = 'affiliate_mb_product_purchased';

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
            'label' => __('MemberPress Product Purchased using Affiliate Referral', 'dollie'),
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
     * @return void
     */
    public function trigger_listener($referral_id)
    {

        if (! function_exists('affwp_get_referral') || ! function_exists('affwp_get_affiliate') || ! function_exists('affwp_get_dynamic_affiliate_coupons')) {
            return;
        }

        $referral = affwp_get_referral($referral_id);
        global $wpdb;

        if ('memberpress' !== (string) $referral->context) {
            return;
        }

        if (! class_exists('\MeprTransaction')) {
            return;
        }

        $reference_id = $referral->reference;
        $transaction = new MeprTransaction($reference_id);

        $user_id = $transaction->user_id;

        $referral = affwp_get_referral($referral->referral_id);
        $membership_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT product_id FROM
            {$wpdb->prefix}mepr_transactions WHERE id = %d",
                $referral->reference
            )
        );
        $affiliate = affwp_get_affiliate($referral->affiliate_id);
        $affiliate_data = get_object_vars($affiliate);
        $user_data = WordPress::get_user_context($user_id);
        $referral_data = get_object_vars($referral);
        $dynamic_coupons = affwp_get_dynamic_affiliate_coupons($referral->affiliate_id, false);

        $context = array_merge(
            $user_data,
            $affiliate_data,
            $referral_data,
            $dynamic_coupons
        );

        $context['product'] = $membership_id;
        $context['product_name'] = get_the_title($membership_id);

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
AffiliateMbProductPurchased::get_instance();
