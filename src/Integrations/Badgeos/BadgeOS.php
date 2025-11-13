<?php

namespace Dollie\SDK\Integrations\Badgeos;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'BadgeOS',
    name: 'Badgeos',
    slug: 'badgeos',
    since: '1.0.0'
)]
/**
 * BadgeOS core integrations file
 *
 * @since 1.0.0
 */
class BadgeOS extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'BadgeOS';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('BadgeOS', 'dollie');
        $this->description = __('BadgeOS lets your site’s users complete tasks and earn badges that recognize their achievement.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/badgeos.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('BadgeOS');
    }
}

IntegrationsController::register(BadgeOS::class);
