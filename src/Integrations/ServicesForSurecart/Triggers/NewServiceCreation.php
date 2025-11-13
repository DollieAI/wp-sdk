<?php

namespace Dollie\SDK\Integrations\ServicesForSureCart\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ss_new_service_created',
    label: 'New Service Created',
    since: '1.0.0'
)]
/**
 * NewServiceCreation.
 * php version 5.6
 *
 * @category NewServiceCreation
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * NewServiceCreation
 *
 * @category NewServiceCreation
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class NewServiceCreation
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ServicesForSureCart';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ss_new_service_created';

    /**
     * Constructor
     *
     * @since  1.0.0
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
            'label' => __('New Service Created', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'surelywp_services_create',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $service_data Service Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($service_data)
    {

        $service_data_arr = [
            'service_setting_id' => $service_data['service_id'],
            'order_id' => $service_data['order_id'],
            'product_id' => $service_data['product_id'],
            'service_status' => $service_data['service_status'],
            'delivery_date' => $service_data['delivery_date'],
        ];
        $context = array_merge($service_data_arr, WordPress::get_user_context($service_data['user_id']));
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
NewServiceCreation::get_instance();
