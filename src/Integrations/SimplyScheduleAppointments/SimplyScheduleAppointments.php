<?php

namespace Dollie\SDK\Integrations\SimplyScheduleAppointments;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'SimplyScheduleAppointments',
    name: 'SimplyScheduleAppointments',
    slug: 'simply-schedule-appointments',
    since: '1.0.0'
)]
/**
 * SimplyScheduleAppointments core integrations file
 *
 * @since 1.0.0
 */
class SimplyScheduleAppointments extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'SimplyScheduleAppointments';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('SimplyScheduleAppointments', 'dollie');
        $this->description = __('Simply Schedule Appointments Booking Plugin is for Consultants and Small Businesses using WordPress.', 'dollie');
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('Simply_Schedule_Appointments');
    }
}

IntegrationsController::register(SimplyScheduleAppointments::class);
