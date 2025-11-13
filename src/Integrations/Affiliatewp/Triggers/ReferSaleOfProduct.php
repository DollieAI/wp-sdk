<?php

namespace Dollie\SDK\Integrations\Affiliatewp\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'affwp_set_referral_status',
    label: 'New Referral/Referral Status Updated',
    since: '1.0.0'
)]
/**
 * ReferSaleOfProduct.
 * php version 5.6
 *
 * @category ReferSaleOfProduct
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ReferSaleOfProduct
 *
 * @category ReferSaleOfProduct
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class ReferSaleOfProduct
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
    public $trigger = 'affwp_set_referral_status';

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
            'label' => __('New Referral/Referral Status Updated', 'dollie'),
            'action' => 'affwp_set_referral_status',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $referral_id Referral ID.
     * @param string $new_status New referral status.
     * @param string $old_status Old referral status.
     * @since 1.0.0*
     *
     * @return void
     */
    public function trigger_listener($referral_id, $new_status, $old_status)
    {
        if (! function_exists('affwp_get_referral') || ! function_exists('affwp_get_affiliate') || ! function_exists('affwp_get_affiliate_user_id')) {
            return;
        }
        $referral = affwp_get_referral($referral_id);
        $affiliate = affwp_get_affiliate($referral->affiliate_id);
        $user_id = affwp_get_affiliate_user_id($referral->affiliate_id);

        $context = array_merge(
            WordPress::get_user_context($user_id),
            get_object_vars($affiliate),
            get_object_vars($referral)
        );

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
ReferSaleOfProduct::get_instance();
