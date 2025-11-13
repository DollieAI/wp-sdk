<?php

namespace Dollie\SDK\Integrations\WoocommerceSubscriptions;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WoocommerceSubscriptions',
    name: 'WoocommerceSubscriptions',
    slug: 'woocommerce-subscriptions',
    since: '1.0.0'
)]
/**
 * WoocommerceSubscriptions core integrations file
 *
 * @since   1.0.0
 */
class WoocommerceSubscriptions extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WoocommerceSubscriptions';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WoocommerceSubscriptions', 'dollie');
        $this->description = __('Woocommerce subscriptions plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/woocommercesubscriptions.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WooCommerce') && class_exists('WC_Subscriptions');
    }
}

IntegrationsController::register(WoocommerceSubscriptions::class);
