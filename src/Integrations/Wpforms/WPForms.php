<?php

namespace Dollie\SDK\Integrations\Wpforms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WPForms',
    name: 'Wpforms',
    slug: 'wpforms',
    since: '1.0.0'
)]
/**
 * WPForms core integrations file
 *
 * @since 1.0.0
 */
class WPForms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPForms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WPForms', 'dollie');
        $this->description = __('Building forms in WordPress can be hard. WPForms makes it easy.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wpforms.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(\WPForms\WPForms::class);
    }
}

IntegrationsController::register(WPForms::class);
