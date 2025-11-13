<?php

namespace Dollie\SDK\Integrations\Storeengine;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'StoreEngine',
    name: 'Storeengine',
    slug: 'storeengine',
    since: '1.0.0'
)]
/**
 * StoreEngine core integrations file
 *
 * @since 1.0.0
 */
class StoreEngine extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'StoreEngine';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('StoreEngine', 'dollie');
        $this->description = __('StoreEngine makes online sales easy with limitless possibilities. From marketing tools to a wide range of payment options.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/servicesforsurecart.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('STOREENGINE_VERSION');
    }
}

IntegrationsController::register(StoreEngine::class);
