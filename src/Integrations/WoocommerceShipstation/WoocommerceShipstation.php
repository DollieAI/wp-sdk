<?php

namespace Dollie\SDK\Integrations\WoocommerceShipstation;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WoocommerceShipstation',
    name: 'WoocommerceShipstation',
    slug: 'woocommerce-shipstation',
    since: '1.0.0'
)]
/**
 * WoocommerceShipstation core integrations file
 *
 * @since   1.0.0
 */
class WoocommerceShipstation extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WoocommerceShipstation';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WoocommerceShipstation', 'dollie');
        $this->description = __('Woocommerce shipstation plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/woocommerceshipstation.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WooCommerce');
    }
}

IntegrationsController::register(WoocommerceShipstation::class);
