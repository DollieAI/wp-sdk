<?php

namespace Dollie\SDK\Integrations\Cartflows;

use Cartflows_Loader;
use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'CartFlows',
    name: 'Cartflows',
    slug: 'cartflows',
    since: '1.0.0'
)]
/**
 * CartFlows core integrations file
 *
 * @since 1.0.0
 */
class CartFlows extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'CartFlows';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('CartFlows', 'dollie');
        $this->description = __('Create beautiful checkout pages & sales flows for WooCommerce.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/cartflows.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(Cartflows_Loader::class);
    }
}

IntegrationsController::register(CartFlows::class);
