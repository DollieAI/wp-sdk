<?php

namespace Dollie\SDK\Integrations\AppointmentHourBooking;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'AppointmentHourBooking',
    name: 'AppointmentHourBooking',
    slug: 'appointment-hour-booking',
    since: '1.0.0'
)]
/**
 * AppointmentHourBooking core integrations file
 *
 * @since 1.0.0
 */
class AppointmentHourBooking extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'AppointmentHourBooking';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Appointment Hour Booking', 'dollie');
        $this->description = __('A WordPress Booking plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/appointment-hour-booking.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('CP_AppBookingPlugin');
    }
}

IntegrationsController::register(AppointmentHourBooking::class);
