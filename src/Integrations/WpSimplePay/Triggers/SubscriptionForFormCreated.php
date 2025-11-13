<?php

namespace Dollie\SDK\Integrations\WpSimplePay\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wsp_subscription_for_form_created',
    label: 'Subscription For Form Created',
    since: '1.0.0'
)]
/**
 * SubscriptionForFormCreated.
 * php version 5.6
 *
 * @category SubscriptionForFormCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SubscriptionForFormCreated
 *
 * @category SubscriptionForFormCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class SubscriptionForFormCreated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WpSimplePay';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wsp_subscription_for_form_created';

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
            'label' => __('Subscription For Form Created', 'dollie'),
            'action' => 'wsp_subscription_for_form_created',
            'common_action' => 'simpay_webhook_subscription_created',
            'function' => [$this, 'trigger_listener'],
            'priority' => 20,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array  $type Stripe webhook event.
     * @param object $object Stripe PaymentIntent.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($type, $object)
    {

        if (! isset($object->metadata->simpay_form_id)) {
            return;
        }
        $form_id = $object->metadata->simpay_form_id;

        if (empty($form_id)) {
            return;
        }

        if (! isset($object->latest_invoice)) {
            return;
        }

        $invoice = $object->latest_invoice;
        /**
         * Ignore line
         *
         * @phpstan-ignore-next-line
         */
        $context['customer'] = $object->customer;

        if (function_exists('simpay_get_form')) {
            $form = simpay_get_form($form_id);
            $context['subscription'] = $form->company_name;
        }
        $context['invoice'] = $invoice;
        /**
         * Ignore line
         *
         * @phpstan-ignore-next-line
         */
        $context['amount_due'] = $object->amount_due;
        /**
         * Ignore line
         *
         * @phpstan-ignore-next-line
         */
        $context['amount_paid'] = $object->amount_paid;
        /**
         * Ignore line
         *
         * @phpstan-ignore-next-line
         */
        $context['amount_remaining'] = $object->amount_remaining;

        $context['wp_simple_pay_form'] = $form_id;
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
SubscriptionForFormCreated::get_instance();
