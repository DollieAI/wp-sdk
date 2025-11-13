<?php

namespace Dollie\SDK\Integrations\BricksBuilder;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'BricksBuilder',
    name: 'BricksBuilder',
    slug: 'bricks-builder',
    since: '1.0.0'
)]
/**
 * Bricks Builder core integrations file
 *
 * @since 1.0.0
 */
class BricksBuilder extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'BricksBuilder';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Bricks', 'dollie');
        $this->description = __('Visual Site Builder for WordPress', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/bricksbuilder.svg';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        $bricks_theme = wp_get_theme('bricks');

        return $bricks_theme->exists();
    }
}

IntegrationsController::register(BricksBuilder::class);
