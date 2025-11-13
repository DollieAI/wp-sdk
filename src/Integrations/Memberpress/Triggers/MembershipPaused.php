<?php

namespace Dollie\SDK\Integrations\Memberpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\MemberPress\MemberPress;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'mepr-event-transaction-paused',
    label: 'Membership Paused',
    since: '1.0.0'
)]
/**
 * MembershipPaused.
 * php version 5.6
 *
 * @category MembershipPaused
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * MembershipPaused
 *
 * @category MembershipPaused
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class MembershipPaused
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
    public $trigger = 'mepr-event-transaction-paused';

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
            'label' => __('Membership Paused', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'mepr_subscription_transition_status',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     * This will trigger for both recurring and non-recurring transactions.
     *
     * @param string $old_status old status.
     * @param string $new_status new status.
     * @param object $sub sub data.
     * @return void
     */
    public function trigger_listener($old_status, $new_status, $sub)
    {
        if ('suspended' !== (string) $new_status) {
            return;
        }
        $membership = MemberPress::get_subscription_context($sub);
        $context = array_merge(
            WordPress::get_user_context($membership['user_id']),
            $membership
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
MembershipPaused::get_instance();
