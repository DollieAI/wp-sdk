<?php

namespace Dollie\SDK\Integrations\Memberpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\MemberPress\MemberPress;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'mepr-event-transaction-completed',
    label: 'Membership Purchased',
    since: '1.0.0'
)]
/**
 * PurchaseMembership.
 * php version 5.6
 *
 * @category PurchaseMembership
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PurchaseMembership
 *
 * @category PurchaseMembership
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class PurchaseMembership
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'MemberPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'mepr-event-transaction-completed';

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
            'label' => __('Membership Purchased', 'dollie'),
            'action' => $this->trigger,
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     * This will trigger for both recurring and non-recurring transactions.
     *
     * @param object $event Event data.
     *
     * @return void
     */
    public function trigger_listener($event)
    {
        if (! class_exists('MeprEvent') || ! $event instanceof \MeprEvent) {
            return;
        }
        $subscription = $event->get_data();
        $context = array_merge(
            WordPress::get_user_context($subscription->user_id),
            MemberPress::get_membership_context($subscription)
        );

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
PurchaseMembership::get_instance();
