<?php

namespace Dollie\SDK\Integrations\WPTravelEngine;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WPTravelEngine',
    name: 'WpTravelEngine',
    slug: 'wp-travel-engine',
    since: '1.0.0'
)]
/**
 * WP Travel Engine core integrations file
 *
 * @since 1.0.0
 */
class WPTravelEngine extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPTravelEngine';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WP Travel Engine', 'dollie');
        $this->description = __('WP Travel Engine is a complete travel booking WordPress plugin to create travel and tour packages.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/WPTravelEngine.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('WP_TRAVEL_ENGINE_VERSION');
    }
}

IntegrationsController::register(WPTravelEngine::class);
