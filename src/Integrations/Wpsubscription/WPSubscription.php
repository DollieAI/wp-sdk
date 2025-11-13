<?php

namespace Dollie\SDK\Integrations\Wpsubscription;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WPSubscription',
    name: 'Wpsubscription',
    slug: 'wpsubscription',
    since: '1.0.0'
)]
/**
 * WPSubscription core integrations file
 *
 * @since 1.0.0
 */
class WPSubscription extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPSubscription';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WPSubscription', 'dollie');
        $this->description = __('WPSubscription for WooCommerce integration.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wpsubscription.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('WP_SUBSCRIPTION_VERSION');
    }
}

IntegrationsController::register(WPSubscription::class);
