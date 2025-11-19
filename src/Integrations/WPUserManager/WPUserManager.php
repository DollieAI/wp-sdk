<?php

namespace Dollie\SDK\Integrations\WPUserManager;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WPUserManager',
    name: 'WpUserManager',
    slug: 'wp-user-manager',
    since: '1.0.0'
)]
/**
 * WP User Manager core integrations file
 *
 * @since 1.0.0
 */
class WPUserManager extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPUserManager';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WP User Manager', 'dollie');
        $this->description = __('WP User Manager is a User management form plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/WPUserManager.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WP_User_Manager');
    }
}

IntegrationsController::register(WPUserManager::class);
