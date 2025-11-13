<?php

namespace Dollie\SDK\Integrations\Jetformbuilder;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'JetFormBuilder',
    name: 'Jetformbuilder',
    slug: 'jetformbuilder',
    since: '1.0.0'
)]
/**
 * JetFormBuilder core integrations file
 *
 * @since 1.0.0
 */
class JetFormBuilder extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'JetFormBuilder';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('JetFormBuilder', 'dollie');
        $this->description = __('A dynamic form creation tool. ', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/JetFormBuilder.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return function_exists('jet_form_builder_init');
    }
}

IntegrationsController::register(JetFormBuilder::class);
