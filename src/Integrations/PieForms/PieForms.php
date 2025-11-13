<?php

namespace Dollie\SDK\Integrations\PieForms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'PieForms',
    name: 'PieForms',
    slug: 'pie-forms',
    since: '1.0.0'
)]
/**
 * Pie Forms core integrations file
 *
 * @since 1.0.0
 */
class PieForms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'PieForms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Pie Forms', 'dollie');
        $this->description = __('Pie Forms is a WordPress Form Builder.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/pieforms.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('PF_PLUGIN_FILE');
    }
}

IntegrationsController::register(PieForms::class);
