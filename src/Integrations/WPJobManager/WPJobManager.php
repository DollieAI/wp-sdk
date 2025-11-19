<?php

namespace Dollie\SDK\Integrations\WPJobManager;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use WP_Job_Manager;

#[Integration(
    id: 'WPJobManager',
    name: 'WpJobManager',
    slug: 'wp-job-manager',
    since: '1.0.0'
)]
/**
 * WPJobManager core integrations file
 *
 * @since 1.0.0
 */
class WPJobManager extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPJobManager';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WPJobManager', 'dollie');
        $this->description = __('Manage job listings from the WordPress admin panel.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wp-job-manager.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(WP_Job_Manager::class);
    }
}

IntegrationsController::register(WPJobManager::class);
