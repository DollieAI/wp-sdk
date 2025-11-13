<?php

namespace Dollie\SDK\Integrations\Happyforms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'HappyForms',
    name: 'Happyforms',
    slug: 'happyforms',
    since: '1.0.0'
)]
/**
 * HappyForms core integrations file
 *
 * @since 1.0.0
 */
class HappyForms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'HappyForms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('HappyForms', 'dollie');
        $this->description = __('A contact form builder plugin. ', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/happyforms.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('HappyForms');
    }
}

IntegrationsController::register(HappyForms::class);
