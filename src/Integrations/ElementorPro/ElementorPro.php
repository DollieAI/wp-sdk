<?php

namespace Dollie\SDK\Integrations\ElementorPro;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use ElementorPro\Plugin;

#[Integration(
    id: 'ElementorPro',
    name: 'ElementorPro',
    slug: 'elementor-pro',
    since: '1.0.0'
)]
/**
 * Elementor core integrations file
 *
 * @since 1.0.0
 */
class ElementorPro extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'ElementorPro';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Elementor', 'dollie');
        $this->description = __('Elementor is the platform web creators choose to build professional WordPress websites, grow their skills, and build their business.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/elementorpro.png';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(Plugin::class);
    }
}

IntegrationsController::register(ElementorPro::class);
