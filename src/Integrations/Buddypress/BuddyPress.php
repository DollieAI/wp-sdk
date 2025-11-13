<?php

namespace Dollie\SDK\Integrations\Buddypress;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'BuddyPress',
    name: 'Buddypress',
    slug: 'buddypress',
    since: '1.0.0'
)]
/**
 * BuddyPress core integrations file
 *
 * @since 1.0.0
 */
class BuddyPress extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'BuddyPress';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('BuddyPress', 'dollie');
        $this->description = __('A WordPress plugin that lets you gamify your WordPress website.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/buddypress.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('BuddyPress');
    }
}

IntegrationsController::register(BuddyPress::class);
