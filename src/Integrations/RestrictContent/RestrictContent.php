<?php

namespace Dollie\SDK\Integrations\RestrictContent;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use RC_Requirements_Check;
use RCP_Requirements_Check;

#[Integration(
    id: 'RestrictContent',
    name: 'RestrictContent',
    slug: 'restrict-content',
    since: '1.0.0'
)]
/**
 * RestrictContent core integrations file
 *
 * @since 1.0.0
 */
class RestrictContent extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'RestrictContent';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Restrict Content Pro', 'dollie');
        $this->description = __('Connect with your fans, faster your community.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/restrict-content.svg';

    }

    /**
     * Get customer context data.
     *
     * @param array|object $membership level.
     *
     * @return array
     */
    public static function get_rcp_membership_detail_context($membership)
    {

        if (! function_exists('rcp_get_membership_level')) {
            return [];
        }

        if (is_object($membership) &&
            method_exists($membership, 'get_object_id') &&
            method_exists($membership, 'get_initial_amount') &&
            method_exists($membership, 'get_recurring_amount') &&
            method_exists($membership, 'get_expiration_date') &&
            method_exists($membership, 'get_customer_id') &&
            method_exists($membership, 'get_status')
        ) {
            $membership_level = rcp_get_membership_level($membership->get_object_id());

            if (is_object($membership_level) && method_exists($membership_level, 'get_id') &&
                method_exists($membership_level, 'get_name')) {
                $context['membership_level_id'] = $membership_level->get_id();
                $context['membership_level'] = $membership_level->get_name();
                $context['membership_initial_payment'] = $membership->get_initial_amount();
                $context['membership_recurring_payment'] = $membership->get_recurring_amount();
                $context['membership_expiry_date'] = $membership->get_expiration_date();
                $context['membership_customer_id'] = $membership->get_customer_id();
                $context['membership_status'] = $membership->get_status();
                if (is_object($membership_level) && method_exists($membership_level, 'get_duration') &&
                method_exists($membership_level, 'get_duration_unit') &&
                method_exists($membership_level, 'get_trial_duration') &&
                method_exists($membership_level, 'get_trial_duration_unit')) {
                    $context['membership_duration'] = $membership_level->get_duration();
                    $context['membership_duration_unit'] = $membership_level->get_duration_unit();
                    $context['membership_trial_duration'] = $membership_level->get_trial_duration();
                    $context['membership_trial_duration_unit'] = $membership_level->get_trial_duration_unit();
                }
            } else {
                $context = [];
            }
        } else {
            $context = [];
        }

        return $context;
    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(RC_Requirements_Check::class) || class_exists(RCP_Requirements_Check::class);
    }
}

IntegrationsController::register(RestrictContent::class);
