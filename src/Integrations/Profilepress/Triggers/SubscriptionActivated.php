<?php

namespace Dollie\SDK\Integrations\Profilepress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Models\Utilities;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'profilepress_subscription_activated',
    label: 'A ProfilePress subscription is activated',
    since: '1.0.0'
)]
/**
 * SubscriptionActivated.
 *
 * @category SubscriptionActivated
 * @since    1.1.5
 */
/**
 * SubscriptionActivated
 */
class SubscriptionActivated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ProfilePress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'profilepress_subscription_activated';

    /**
     * Constructor
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register trigger.
     *
     * @param array $triggers trigger data.
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('A ProfilePress subscription is activated', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'ppress_subscription_activated',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1, // Hook passes SubscriptionEntity.
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $subscription Subscription.
     * @return void|array
     */
    public function trigger_listener($subscription)
    {
        if (! class_exists('\ProfilePress\Core\Membership\Models\Subscription\SubscriptionEntity')) {
            return [
                'status' => 'error',
                'response' => __('ProfilePress Subscription Entity class not found. Please ensure ProfilePress is properly installed.', 'dollie'),

            ];
        }

        $subscription_data = Utilities::object_to_array($subscription);

        // Pass subscription object directly.
        $context = [
            'subscription' => $subscription_data,
        ];

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

SubscriptionActivated::get_instance();
