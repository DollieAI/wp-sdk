<?php

namespace Dollie\SDK\Integrations\BetterMessages;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'BetterMessages',
    name: 'BetterMessages',
    slug: 'better-messages',
    since: '1.0.0'
)]
/**
 * Better Messages core integrations file
 *
 * @since 1.0.0
 */
class BetterMessages extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'BetterMessages';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Better Messages', 'dollie');
        $this->description = __('Better Messages – is realtime private messaging system for WordPress, BuddyPress, BuddyBoss Platform, Ultimate Member, PeepSo and any other WordPress powered websites.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/better-messages.svg';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        if (class_exists('Better_Messages_Functions')) {
            return true;
        } else {
            return false;
        }
    }
}

IntegrationsController::register(BetterMessages::class);
