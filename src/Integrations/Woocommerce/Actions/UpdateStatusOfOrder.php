<?php

namespace Dollie\SDK\Integrations\Woocommerce\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use WC_Order;

#[Action(
    id: 'wc_update_status_of_order',
    label: 'Update Status of Order.',
    since: '1.0.0'
)]
/**
 * UpdateStatusOfOrder.
 * php version 5.6
 *
 * @category UpdateStatusOfOrder
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UpdateStatusOfOrder
 *
 * @category UpdateStatusOfOrder
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UpdateStatusOfOrder extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WooCommerce';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'wc_update_status_of_order';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Update Status of Order.', 'dollie'),
            'action' => 'wc_update_status_of_order',
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user_id.
     * @param int   $automation_id automation_id.
     * @param array $fields fields.
     * @param array $selected_options selectedOptions.
     *
     * @return object|array|null
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $order_id = $selected_options['order_id'];
        $status = $selected_options['status'];

        $order = wc_get_order($order_id);

        if (! $order instanceof WC_Order) {
            return [
                'status' => 'error',
                'message' => 'No order found with the specified Order ID.',
            ];
        }

        $order->update_status($status);

        return WooCommerce::get_order_context($order_id);
    }
}

UpdateStatusOfOrder::get_instance();
