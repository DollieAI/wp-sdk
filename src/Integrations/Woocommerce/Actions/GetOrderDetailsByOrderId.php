<?php

namespace Dollie\SDK\Integrations\Woocommerce\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'wc_get_order_details_by_order_id',
    label: 'Get Order Details by Order ID',
    since: '1.0.0'
)]
/**
 * GetOrderDetailsByOrderID.
 * php version 5.6
 *
 * @category GetOrderDetailsByOrderID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GetOrderDetailsByOrderID
 *
 * @category GetOrderDetailsByOrderID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetOrderDetailsByOrderID extends AutomateAction
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
    public $action = 'wc_get_order_details_by_order_id';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get Order Details by Order ID', 'dollie'),
            'action' => 'wc_get_order_details_by_order_id',
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
     * @return array|null
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $order_id = $selected_options['order_id'];

        $order = wc_get_order($order_id);
        if (empty($order)) {
            return [
                'status' => 'error',
                'message' => 'There is no order associated with this Order ID.',
            ];
        }

        return WooCommerce::get_order_context($order_id);
    }
}

GetOrderDetailsByOrderID::get_instance();
