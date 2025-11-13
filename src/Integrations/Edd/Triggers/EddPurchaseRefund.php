<?php

namespace Dollie\SDK\Integrations\Edd\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\EDD\EDD;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'edd_user_purchase_refund',
    label: 'Stripe Payment Refunded',
    since: '1.0.0'
)]
/**
 * EDDPurchaseRefund.
 * php version 5.6
 *
 * @category EDDPurchaseRefund
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * EDDPurchaseRefund
 *
 * @category EDDPurchaseRefund
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class EDDPurchaseRefund
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'EDD';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'edd_user_purchase_refund';

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
            'label' => __('Stripe Payment Refunded', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'edds_payment_refunded',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $order_id The entry that was just created.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($order_id)
    {
        $order_detail = edd_get_payment($order_id);

        if (empty($order_detail)) {
            return;
        }

        $context = EDD::get_purchase_refund_context($order_detail);

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
EDDPurchaseRefund::get_instance();
