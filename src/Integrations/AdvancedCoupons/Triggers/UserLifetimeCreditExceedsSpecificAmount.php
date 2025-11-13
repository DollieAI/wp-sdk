<?php

namespace Dollie\SDK\Integrations\AdvancedCoupons\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_lifetime_credit_exceeds_specific_amount',
    label: 'User Lifetime Credit Exceeds Specific Amount',
    since: '1.0.0'
)]
/**
 * UserLifetimeCreditsExceedsSpecificAmount.
 * php version 5.6
 *
 * @category UserLifetimeCreditsExceedsSpecificAmount
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserLifetimeCreditsExceedsSpecificAmount
 *
 * @category UserLifetimeCreditsExceedsSpecificAmount
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserLifetimeCreditsExceedsSpecificAmount
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'AdvancedCoupons';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_lifetime_credit_exceeds_specific_amount';

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
            'label' => __('User Lifetime Credit Exceeds Specific Amount', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'acfw_create_store_credit_entry',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $data Data.
     * @return void
     */
    public function trigger_listener($data)
    {

        global $wpdb;
        if (isset($data['type']) && 'increase' !== $data['type']) {
            return;
        }

        $user_id = (isset($data['user_id'])) ? intval($data['user_id']) : 0;

        if (0 === $user_id) {
            return;
        }

        $new_amount = floatval($data['amount']);

        if (function_exists('ACFWF')) {
            $coupon_data = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT entry_type,entry_action,CONVERT(entry_amount, DECIMAL(%d,%d)) AS amount
						FROM {$wpdb->prefix}acfw_store_credits
						WHERE user_id = %d",
                    \ACFWF()->Store_Credits_Calculate->get_decimal_precision(),
                    wc_get_price_decimals(),
                    $user_id
                ),
                ARRAY_A
            );

            $total_amount = 0;
            foreach ($coupon_data as $value) {
                if (isset($value['entry_type']) && 'increase' === $value['entry_type']) {
                    $total_amount += floatval($value['amount']);
                }
            }

            $current_balance = apply_filters('acfw_filter_amount', $total_amount);

            $trigger_data['credit_amount'] = $current_balance;
        }
        $trigger_data['added_amount'] = $new_amount;

        $context = array_merge($trigger_data, WordPress::get_user_context($user_id));

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
UserLifetimeCreditsExceedsSpecificAmount::get_instance();
