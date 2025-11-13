<?php

namespace Dollie\SDK\Integrations\SimplyScheduleAppointments\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ssa_appointment_rescheduled',
    label: 'Appointment Rescheduled',
    since: '1.0.0'
)]
/**
 * AppointmentRescheduled.
 * php version 5.6
 *
 * @category AppointmentRescheduled
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AppointmentRescheduled
 *
 * @category AppointmentRescheduled
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AppointmentRescheduled
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'SimplyScheduleAppointments';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ssa_appointment_rescheduled';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register action.
     *
     * @param array $triggers trigger data.
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Appointment Rescheduled', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'ssa/appointment/rescheduled',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $appointment_id       appointment id.
     * @param array $data       Booking details.
     * @param array $data_before Old Booking details.
     *
     * @return void
     */
    public function trigger_listener($appointment_id, $data, $data_before)
    {

        if (empty($data)) {
            return;
        }
        if ($data_before['start_date'] === $data['start_date']) {
            return;
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $data,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
AppointmentRescheduled::get_instance();
