<?php

namespace Dollie\SDK\Integrations\Edd\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'edd_order_status_changes',
    label: 'Order Status Changes',
    since: '1.0.0'
)]
/**
 * EDDOrderStatusChanges.
 * php version 5.6
 *
 * @category EDDOrderStatusChanges
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * EDDOrderStatusChanges
 *
 * @category EDDOrderStatusChanges
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class EDDOrderStatusChanges
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'EDD';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'edd_order_status_changes';

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
     * @param array $triggers Trigger data.
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Order Status Changes', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'edd_update_payment_status',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $order_id   The order ID.
     * @param string $new_status The new status.
     * @param string $old_status The old status.
     *
     * @since 1.0.0
     * @return void
     */
    public function trigger_listener($order_id, $new_status, $old_status)
    {
        if (! function_exists('edd_get_order')) {
            return;
        }

        $order = edd_get_order($order_id);

        $downloads = [];

        foreach ($order->get_items() as $item) {
            $downloads[] = [
                'id' => $item->product_id,
                'name' => get_the_title($item->product_id),
                'quantity' => $item->quantity,
                'price' => $item->price,
            ];
        }

        $context = [
            'order_id' => $order_id,
            'customer_id' => $order->customer_id,
            'email' => $order->email,
            'total' => $order->total,
            'downloads' => $downloads,
            'date_created' => $order->date_created,
        ];

        $context['old_status'] = $old_status;
        $context['new_status'] = $new_status;

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
EDDOrderStatusChanges::get_instance();
