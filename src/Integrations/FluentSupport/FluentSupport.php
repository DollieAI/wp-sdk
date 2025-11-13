<?php

namespace Dollie\SDK\Integrations\FluentSupport;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'FluentSupport',
    name: 'FluentSupport',
    slug: 'fluent-support',
    since: '1.0.0'
)]
/**
 * Fluent Support core integrations file
 *
 * @since 1.0.0
 */
class FluentSupport extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'FluentSupport';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Fluent Support', 'dollie');
        $this->description = __('Fluent Support is a WordPress Customer Support plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/fluentsupport.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('FLUENT_SUPPORT_VERSION');
    }
}

IntegrationsController::register(FluentSupport::class);
