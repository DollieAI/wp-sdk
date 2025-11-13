<?php

namespace Dollie\SDK\Integrations\Profilepress;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'ProfilePress',
    name: 'Profilepress',
    slug: 'profilepress',
    since: '1.0.0'
)]
/**
 * ProfilePress integration file
 *
 * @since 1.0.0
 */
/**
 * ProfilePress Integration
 */
class ProfilePress extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID of the integration
     *
     * @var string
     */
    protected $id = 'ProfilePress';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('ProfilePress', 'dollie');
        $this->description = __('Modern WordPress Membership Plugin.', 'dollie');
        $this->icon_url = \DOLLIE_SDK_URL . 'assets/icons/profilepress.png';

    }

    /**
     * Check plugin is installed.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('PPRESS_VERSION_NUMBER');
    }
}

IntegrationsController::register(ProfilePress::class);
