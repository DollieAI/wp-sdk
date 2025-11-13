<?php

namespace Dollie\SDK\Integrations\GravityForm;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'GravityForms',
    name: 'GravityForm',
    slug: 'gravity-form',
    since: '1.0.0'
)]
/**
 * Gravity Forms core integrations file
 *
 * @since 1.0.0
 */
class GravityForms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'GravityForms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Gravity Forms', 'dollie');
        $this->description = __('Gravity Forms is a WordPress Form Builder.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/gravityform.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('RGForms');
    }
}

IntegrationsController::register(GravityForms::class);
