<?php

namespace Dollie\SDK\Integrations\Fluentcommunity;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'FluentCommunity',
    name: 'Fluentcommunity',
    slug: 'fluentcommunity',
    since: '1.0.0'
)]
/**
 * Fluent Community core integrations file
 *
 * @since 1.0.0
 */
class FluentCommunity extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'FluentCommunity';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Fluent Community', 'dollie');
        $this->description = __('Simplifying Community Engagement.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/fluentcommunity.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('FLUENT_COMMUNITY_PLUGIN_VERSION');
    }
}

IntegrationsController::register(FluentCommunity::class);
