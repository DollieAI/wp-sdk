<?php

namespace Dollie\SDK\Integrations\Forminator;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'Forminator',
    name: 'Forminator',
    slug: 'forminator',
    since: '1.0.0'
)]
/**
 * Forminator core integrations file
 *
 * @since 1.0.0
 */
class Forminator extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'Forminator';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Forminator', 'dollie');
        $this->description = __('A form builder plugin. ', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/Forminator.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('Forminator');
    }
}

IntegrationsController::register(Forminator::class);
