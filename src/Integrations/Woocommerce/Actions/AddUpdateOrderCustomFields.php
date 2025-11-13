<?php

namespace Dollie\SDK\Integrations\Woocommerce\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WooCommerce\WooCommerce;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use WC_Order;

#[Action(
    id: 'wc_add_update_custom_fields',
    label: 'Add or Update Custom Fields',
    since: '1.0.0'
)]
/**
 * AddUpdateOrderCustomFields.
 * php version 5.6
 *
 * @category AddUpdateOrderCustomFields
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddUpdateOrderCustomFields
 *
 * @category AddUpdateOrderCustomFields
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddUpdateOrderCustomFields extends AutomateAction
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
    public $action = 'wc_add_update_custom_fields';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Add or Update Custom Fields', 'dollie'),
            'action' => 'wc_add_update_custom_fields',
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
        $meta = $selected_options['order_meta'];
        $order = wc_get_order($order_id);

        if (! $order instanceof WC_Order) {
            return [
                'status' => 'error',
                'message' => 'No order found with the specified Order ID.',
            ];
        }

        foreach ($meta as $fields) {
            $meta_key = $fields['meta_key'];
            $meta_value = $fields['meta_value'];
            $order->update_meta_data($meta_key, $meta_value);
        }
        $order->save();

        return WooCommerce::get_order_context($order_id);
    }
}

AddUpdateOrderCustomFields::get_instance();
