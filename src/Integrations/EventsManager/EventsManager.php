<?php

namespace Dollie\SDK\Integrations\EventsManager;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'EventsManager',
    name: 'EventsManager',
    slug: 'events-manager',
    since: '1.0.0'
)]
/**
 * Events Manager integrations file
 *
 * @since 1.0.0
 */
class EventsManager extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'EventsManager';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Events Manager', 'dollie');
        $this->description = __('Easy event registration.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/the-events-calendar.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        if (class_exists('EM_Events')) {
            return true;
        } else {
            return false;
        }
    }
}

IntegrationsController::register(EventsManager::class);
