<?php

namespace Dollie\SDK\Integrations\RestrictContent\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\RestrictContent\RestrictContent;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'restrict_content_membership_purchased',
    label: 'Membership Purchased',
    since: '1.0.0'
)]
/**
 * RestrictContentMembershipPurchased.
 * php version 5.6
 *
 * @category RestrictContentMembershipPurchased
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RestrictContentMembershipPurchased
 *
 * @category RestrictContentMembershipPurchased
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class RestrictContentMembershipPurchased
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'RestrictContent';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'restrict_content_membership_purchased';

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
            'common_action' => 'rcp_membership_post_activate',
            'function' => [$this, 'trigger_listener'],
            'priority' => 5,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int    $membership_id ID of the membership.
     * @param object $membership Membership object.
     * @since 1.0.0
     *
     * @return void|bool
     */
    public function trigger_listener($membership_id, $membership)
    {

        if (! function_exists('rcp_get_membership')) {
            return;
        }
        $membership = rcp_get_membership($membership_id);

        $user_id = $membership->get_user_id();

        if (! $user_id) {
            return false;
        }

        $context = array_merge(
            WordPress::get_user_context($user_id),
            RestrictContent::get_rcp_membership_detail_context($membership)
        );

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'user_id' => $user_id,
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
RestrictContentMembershipPurchased::get_instance();
