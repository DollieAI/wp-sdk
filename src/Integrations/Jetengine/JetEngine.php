<?php

namespace Dollie\SDK\Integrations\Jetengine;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'JetEngine',
    name: 'Jetengine',
    slug: 'jetengine',
    since: '1.0.0'
)]
/**
 * JetEngine core integrations file
 *
 * @since   1.0.0
 */
class JetEngine extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'JetEngine';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('JetEngine', 'dollie');
        $this->description = __(
            'WordPress Dynamic Content Plugin for
		Elementor.',
            'dollie'
        );
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/jetengine.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('\Jet_Engine');
    }
}

IntegrationsController::register(JetEngine::class);
