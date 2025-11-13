<?php

namespace Dollie\SDK\Integrations\Wpsubscription\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wp_subscription_pending_cancellation',
    label: 'Subscription Pending Cancellation',
    since: '1.0.0'
)]
/**
 * SubscriptionPendingCancellation.
 * php version 5.6
 *
 * @category SubscriptionPendingCancellation
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SubscriptionPendingCancellation
 *
 * @category SubscriptionPendingCancellation
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class SubscriptionPendingCancellation
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPSubscription';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wp_subscription_pending_cancellation';

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
            'label' => __('Subscription Pending Cancellation', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'subscrpt_subscription_pending_cancellation',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $subscription_id Subscription ID.
     * @return void
     */
    public function trigger_listener($subscription_id)
    {
        if (empty($subscription_id)) {
            return;
        }

        $subscription = get_post($subscription_id);
        if (! $subscription || 'subscrpt_order' !== $subscription->post_type) {
            return;
        }

        $context = [
            'subscription_id' => $subscription_id,
            'subscription' => $subscription,
            'user_id' => $subscription->post_author,
        ];

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

SubscriptionPendingCancellation::get_instance();
