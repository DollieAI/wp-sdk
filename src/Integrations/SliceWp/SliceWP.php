<?php

namespace Dollie\SDK\Integrations\SliceWP;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'SliceWP',
    name: 'SliceWp',
    slug: 'slice-wp',
    since: '1.0.0'
)]
/**
 * SliceWP core integrations file
 *
 * @since 1.0.0
 */
class SliceWP extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'SliceWP';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('SliceWP', 'dollie');
        $this->description = __('Affiliate Plugin for WordPress.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/affiliatewp.svg';

    }

    /**
     * Is Plugin dependent plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('SLICEWP_VERSION');
    }
}

IntegrationsController::register(SliceWP::class);
