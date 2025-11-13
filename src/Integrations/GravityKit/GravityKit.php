<?php

namespace Dollie\SDK\Integrations\GravityKit;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'GravityKit',
    name: 'GravityKit',
    slug: 'gravity-kit',
    since: '1.0.0'
)]
/**
 * Gravity Kit core integrations file
 *
 * @since 1.0.0
 */
class GravityKit extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'GravityKit';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Gravity Kit', 'dollie');
        $this->description = __('Gravity Kit is a WordPress Plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/gravitykit.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        if (class_exists('GravityView_Plugin') && class_exists('GFFormsModel')) {
            return true;
        } else {
            return false;
        }
    }
}

IntegrationsController::register(GravityKit::class);
