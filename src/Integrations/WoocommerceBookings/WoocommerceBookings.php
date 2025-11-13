<?php

namespace Dollie\SDK\Integrations\WoocommerceBookings;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WoocommerceBookings',
    name: 'WoocommerceBookings',
    slug: 'woocommerce-bookings',
    since: '1.0.0'
)]
/**
 * WoocommerceBookings core integrations file
 *
 * @since   1.0.0
 */
class WoocommerceBookings extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WoocommerceBookings';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Woocommerce Bookings', 'dollie');
        $this->description = __('WooCommerce Bookings is an extension for WooCommerce that allow customers to book appointments, make reservations or rent equipment without leaving your site.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/woocommercebookings.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WooCommerce') && class_exists('WC_Bookings');
    }
}

IntegrationsController::register(WoocommerceBookings::class);
