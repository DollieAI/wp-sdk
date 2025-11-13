<?php

namespace Dollie\SDK\Integrations\AdvancedCoupons\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_spends_store_credit',
    label: 'User Spends Store Credit',
    since: '1.0.0'
)]
/**
 * UserSpendsStoreCredit.
 * php version 5.6
 *
 * @category UserSpendsStoreCredit
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserSpendsStoreCredit
 *
 * @category UserSpendsStoreCredit
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserSpendsStoreCredit
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
    public $trigger = 'user_spends_store_credit';

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
            'label' => __('User Spends Store Credit', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'acfw_after_order_paid_with_store_credits',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $amount Amount.
     * @param int    $new_balance New Balance.
     * @param object $order Order.
     * @param int    $store_credit_entry Store Credit Entry.
     * @return void
     */
    public function trigger_listener($amount, $new_balance, $order, $store_credit_entry)
    {

        if (empty($amount) || 0 == $amount) {
            return;
        }

        $balance = floatval($amount);
        $order_id = 0;
        if (is_object($order)) {
            if (method_exists($order, 'get_id')) {
                $order_id = $order->get_id();
                if (method_exists($order, 'get_customer_id')) {
                    $user_id = $order->get_customer_id();

                    $trigger_data['credit_amount'] = $balance;
                    $context = array_merge(
                        $trigger_data,
                        (array) WooCommerce::get_order_context($order_id),
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
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
UserSpendsStoreCredit::get_instance();
