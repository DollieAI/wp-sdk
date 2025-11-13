<?php

namespace Dollie\SDK\Integrations\Wployalty;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WPLoyalty',
    name: 'Wployalty',
    slug: 'wployalty',
    since: '1.0.0'
)]
/**
 * WPLoyalty integrations file
 *
 * @since 1.0.0
 */
class WPLoyalty extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPLoyalty';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WPLoyalty', 'dollie');
        $this->description = __(
            'The best WordPress forum plugin, 
		full-fledged yet easy and light forum solution for your WordPress website. 
		The only forum software with multiple forum layouts.',
            'dollie'
        );
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wployalty.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WooCommerce') && class_exists('\Wlr\App\Router');
    }
}

IntegrationsController::register(WPLoyalty::class);
