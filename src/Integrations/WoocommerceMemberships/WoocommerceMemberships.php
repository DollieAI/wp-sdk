<?php

namespace Dollie\SDK\Integrations\WoocommerceMemberships;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WoocommerceMemberships',
    name: 'WoocommerceMemberships',
    slug: 'woocommerce-memberships',
    since: '1.0.0'
)]
/**
 * WoocommerceMemberships core integrations file
 *
 * @since   1.0.0
 */
class WoocommerceMemberships extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WoocommerceMemberships';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WoocommerceMemberships', 'dollie');
        $this->description = __('Woocommerce memberships plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/woocommercememberships.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WooCommerce') && class_exists('WC_Memberships_Loader');
    }
}

IntegrationsController::register(WoocommerceMemberships::class);
