<?php

namespace Dollie\SDK\Integrations\Affiliatewp\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'affwp_register_user_approval',
    label: 'Affiliate Awaiting Approval',
    since: '1.0.0'
)]
/**
 * AffiliateAwaitApproval.
 * php version 5.6
 *
 * @category AffiliateAwaitApproval
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AffiliateAwaitApproval
 *
 * @category AffiliateAwaitApproval
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AffiliateAwaitApproval
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'AffiliateWP';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'affwp_register_user_approval';

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
            'label' => __('Affiliate Awaiting Approval', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'affwp_set_affiliate_status',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $affiliate_id Affiliate ID.
     * @param string $status Affiliate status.
     * @param string $old_status Affiliate old status.
     * @return string|void
     */
    public function trigger_listener($affiliate_id, $status, $old_status)
    {
        if (! function_exists('affwp_get_affiliate_user_id') || ! function_exists('affwp_get_affiliate')) {
            return;
        }
        if ('pending' !== $status) {
            return $status;
        }

        $user_id = affwp_get_affiliate_user_id($affiliate_id);

        $affiliate = affwp_get_affiliate($affiliate_id);

        $context = array_merge(
            WordPress::get_user_context($user_id),
            get_object_vars($affiliate)
        );

        $context['status'] = $status;

        $user_id = ap_get_current_user_id();

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $user_id,
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
AffiliateAwaitApproval::get_instance();
