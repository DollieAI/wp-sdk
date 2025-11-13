<?php

namespace Dollie\SDK\Integrations\Sureforms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use SRFM\Plugin_Loader;

#[Integration(
    id: 'SureForms',
    name: 'Sureforms',
    slug: 'sureforms',
    since: '1.0.0'
)]
/**
 * SureForms core integrations file
 *
 * @since 1.0.0
 */
class SureForms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'SureForms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('SureForms', 'dollie');
        $this->description = __('A simple yet powerful way to create modern forms for your website.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/sureforms.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(Plugin_Loader::class);
    }
}

IntegrationsController::register(SureForms::class);
