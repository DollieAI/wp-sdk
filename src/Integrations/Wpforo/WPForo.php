<?php

namespace Dollie\SDK\Integrations\Wpforo;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'wpForo',
    name: 'Wpforo',
    slug: 'wpforo',
    since: '1.0.0'
)]
/**
 * WPForo core integrations file
 *
 * @since 1.0.0
 */
class WPForo extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'wpForo';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('wpForo', 'dollie');
        $this->description = __('The best WordPress forum plugin, full-fledged yet easy and light forum solution for your WordPress website. The only forum software with multiple forum layouts.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wpforo.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('WPFORO_VERSION');
    }
}

IntegrationsController::register(WPForo::class);
