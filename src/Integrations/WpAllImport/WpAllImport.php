<?php

namespace Dollie\SDK\Integrations\WpAllImport;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WpAllImport',
    name: 'WpAllImport',
    slug: 'wp-all-import',
    since: '1.0.0'
)]
/**
 * WpAllImport core integrations file
 *
 * @since   1.0.0
 */
class WpAllImport extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WpAllImport';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WPAllImport', 'dollie');
        $this->description = __('WP All Import plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wp-all-import.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('PMXI_Plugin');
    }
}

IntegrationsController::register(WpAllImport::class);
