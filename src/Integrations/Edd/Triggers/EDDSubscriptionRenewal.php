<?php

namespace Dollie\SDK\Integrations\Edd\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\EDD\EDD;
use Dollie\SDK\Traits\SingletonLoader;
use EDD_Payment;

#[Trigger(
    id: 'edd_subscription_renewal',
    label: 'Subscription Renewal',
    since: '1.0.0'
)]
/**
 * EDDSubscriptionRenewal.
 * php version 5.6
 *
 * @category EDDSubscriptionRenewal
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * EDDSubscriptionRenewal
 *
 * @category EDDSubscriptionRenewal
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class EDDSubscriptionRenewal
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
    public $trigger = 'edd_subscription_renewal';

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
            'label' => __('Subscription Renewal', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'edd_subscription_post_renew',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $subscription_id Subscription id.
     * @param string $expiration expiration time.
     * @param array  $subscription subscription time.
     * @param int    $payment_id payment id.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($subscription_id, $expiration, $subscription, $payment_id)
    {
        if (! class_exists('\EDD_Payment')) {
            return;
        }
        $payment = new EDD_Payment($payment_id);

        if (empty($payment->cart_details)) {
            return;
        }
        $context = EDD::get_product_purchase_context($payment);

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
EDDSubscriptionRenewal::get_instance();
