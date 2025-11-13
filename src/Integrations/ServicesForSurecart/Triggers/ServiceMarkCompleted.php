<?php

namespace Dollie\SDK\Integrations\ServicesForSureCart\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ss_service_mark_completed',
    label: 'Service Mark Completed',
    since: '1.0.0'
)]
/**
 * ServiceMarkCompleted.
 * php version 5.6
 *
 * @category ServiceMarkCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ServiceMarkCompleted
 *
 * @category ServiceMarkCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class ServiceMarkCompleted
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
    public $trigger = 'ss_service_mark_completed';

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
            'label' => __('Service Mark Completed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'surelywp_services_mark_complete',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int $service_id Service ID.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($service_id)
    {

        global $wpdb;

        $result = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}surelywp_sv_services WHERE service_id = %d", $service_id), ARRAY_A);
        $user_data = WordPress::get_user_context($result['user_id']);
        unset($result['user_id']);
        $context = array_merge($result, $user_data);
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
ServiceMarkCompleted::get_instance();
