<?php

namespace Dollie\SDK\Integrations\Gamipress;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'GamiPress',
    name: 'Gamipress',
    slug: 'gamipress',
    since: '1.0.0'
)]
/**
 * GamiPress core integrations file
 *
 * @since 1.0.0
 */
class GamiPress extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'GamiPress';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('GamiPress', 'dollie');
        $this->description = __('A WordPress plugin that lets you gamify your WordPress website.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/gamipress.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('GamiPress');
    }
}

IntegrationsController::register(GamiPress::class);
