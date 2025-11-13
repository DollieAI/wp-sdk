<?php

namespace Dollie\SDK\Integrations\Woocommerce\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wc_customer_created',
    label: 'Customer Created',
    since: '1.0.0'
)]
/**
 * CustomerCreated.
 * php version 5.6
 *
 * @category CustomerCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CustomerCreated
 *
 * @category CustomerCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CustomerCreated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WooCommerce';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wc_customer_created';

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
            'event_name' => 'wc_customer_created_trigger',
            'label' => __('Customer Created', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wc_customer_created_trigger',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $context        Context Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($context)
    {

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

CustomerCreated::get_instance();
