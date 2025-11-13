<?php

namespace Dollie\SDK\Integrations\Amelia;

use AmeliaBooking\Plugin;
use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'Amelia',
    name: 'Amelia',
    slug: 'amelia',
    since: '1.0.0'
)]
/**
 * Amelia core integrations file
 *
 * @since 1.0.0
 */
class Amelia extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'Amelia';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Amelia', 'dollie');
        $this->description = __('A WordPress plugin that allows you to easily schedule and manage appointments and bookings on your website.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/amelia.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('\AmeliaBooking\Plugin');
    }
}

IntegrationsController::register(Amelia::class);
