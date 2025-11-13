<?php

namespace Dollie\SDK\Integrations\WoocommerceMemberships\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wc_user_membership_plan',
    label: 'A user is added to a membership plan',
    since: '1.0.0'
)]
/**
 * AddUserMembershipPlan.
 * php version 5.6
 *
 * @category AddUserMembershipPlan
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddUserMembershipPlan
 *
 * @category AddUserMembershipPlan
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AddUserMembershipPlan
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WoocommerceMemberships';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wc_user_membership_plan';

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
            'label' => __('A user is added to a membership plan', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wc_memberships_user_membership_saved',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     *  Trigger listener
     *
     * @param object $membership_plan Membership Plan.
     * @param array  $data Data.
     *
     * @return void
     */
    public function trigger_listener($membership_plan, $data)
    {

        if (0 === $data['user_id']) {
            return;
        }

        // If membership is active only.
        if (function_exists('wc_memberships_get_user_membership')) {
            $user_membership = wc_memberships_get_user_membership($data['user_membership_id']);
            if (! $user_membership->is_active()) {
                return;
            }
        }

        if (is_object($membership_plan)) {
            $membership_plan = $membership_plan;
        }

        if (property_exists($membership_plan, 'id')) {
            $membership_plan_type = get_post_meta($membership_plan->id, '_access_method', true);

            if ('purchase' === $membership_plan_type) {
                $order_id = get_post_meta($data['user_membership_id'], '_order_id', true);
            }
            $context['membership_plan'] = $membership_plan->id;
        }

        if (property_exists($membership_plan, 'name')) {
            $context['membership_plan_name'] = $membership_plan->name;
        }
        $context['user'] = WordPress::get_user_context($data['user_id']);

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
AddUserMembershipPlan::get_instance();
