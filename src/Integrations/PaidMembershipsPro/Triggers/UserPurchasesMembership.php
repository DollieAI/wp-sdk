<?php

namespace Dollie\SDK\Integrations\PaidMembershipsPro\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_purchases_membership',
    label: 'A user purchases a membership',
    since: '1.0.0'
)]
/**
 * UserPurchasesMembership.
 * php version 5.6
 *
 * @category UserPurchasesMembership
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserPurchasesMembership
 *
 * @category UserPurchasesMembership
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserPurchasesMembership
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'PaidMembershipsPro';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_purchases_membership';

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
            'label' => __('A user purchases a membership', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'pmpro_after_checkout',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int    $user_id User ID.
     * @param object $morder Cancel Level.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($user_id, $morder)
    {

        if (method_exists($morder, 'getMembershipLevel')) {
            $membership = $morder->getMembershipLevel();
            $membership_id = $membership->id;

            $context['membership_id'] = $membership->id;
            $context['membership'] = $membership;
            $context['user'] = WordPress::get_user_context($user_id);
            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
UserPurchasesMembership::get_instance();
