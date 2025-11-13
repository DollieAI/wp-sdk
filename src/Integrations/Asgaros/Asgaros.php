<?php

namespace Dollie\SDK\Integrations\Asgaros;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'Asgaros',
    name: 'Asgaros',
    slug: 'asgaros',
    since: '1.0.0'
)]
/**
 * Asgaros core integrations file
 *
 * @since 1.0.0
 */
class Asgaros extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'Asgaros';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Asgaros', 'dollie');
        $this->description = __('The best WordPress forum plugin, full-fledged yet easy and light forum solution for your WordPress website. The only forum software with multiple forum layouts.', 'dollie');

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('AsgarosForum');
    }
}

IntegrationsController::register(Asgaros::class);
