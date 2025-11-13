<?php

namespace Dollie\SDK\Integrations\NinjaForms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'NinjaForms',
    name: 'NinjaForms',
    slug: 'ninja-forms',
    since: '1.0.0'
)]
/**
 * Met Form core integrations file
 *
 * @since 1.0.0
 */
class NinjaForms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'NinjaForms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Ninja Forms', 'dollie');
        $this->description = __('Ninja Forms is a WordPress Form Builder.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/ninjaforms.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(\Ninja_Forms::class);
    }
}

IntegrationsController::register(NinjaForms::class);
