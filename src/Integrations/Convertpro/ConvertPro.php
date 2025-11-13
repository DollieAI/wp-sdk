<?php

namespace Dollie\SDK\Integrations\Convertpro;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'ConvertPro',
    name: 'Convertpro',
    slug: 'convertpro',
    since: '1.0.0'
)]
/**
 * ConvertPro core integrations file
 *
 * @since 1.0.0
 */
class ConvertPro extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'ConvertPro';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('ConvertPro', 'dollie');
        $this->description = __('A WordPress plugin to convert visitors into leads, subscribers and customers. ', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/convertpro.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('\Cp_V2_Loader');
    }
}

IntegrationsController::register(ConvertPro::class);
