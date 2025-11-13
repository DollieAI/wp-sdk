<?php

namespace Dollie\SDK\Integrations\AppointmentHourBooking\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ahb_new_appointment',
    label: 'New Appointment Booked',
    since: '1.0.0'
)]
/**
 * NewAppointment.
 * php version 5.6
 *
 * @category NewAppointment
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * NewAppointment
 *
 * @category NewAppointment
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class NewAppointment
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'AppointmentHourBooking';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ahb_new_appointment';

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
            'label' => __('New Appointment Booked', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'cpappb_process_data',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $params Appointment Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($params)
    {
        $context = $params;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
NewAppointment::get_instance();
