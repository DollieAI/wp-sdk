<?php

namespace Dollie\SDK\Integrations\Profilepress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'ppress_get_order_details_by_id',
    label: 'Get Order Details by ID',
    since: '1.0.0'
)]
/**
 * GetOrderDetailsByID.
 * php version 5.6
 *
 * @category GetOrderDetailsByID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.1.5
 */
/**
 * GetOrderDetailsByID
 *
 * @category GetOrderDetailsByID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetOrderDetailsByID extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ProfilePress';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'ppress_get_order_details_by_id';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get Order Details by ID', 'dollie'),
            'action' => $this->action,
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
     * @return object|array|void
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! class_exists('\ProfilePress\Core\Membership\Models\Order\OrderFactory')) {
            return [
                'status' => __('error', 'dollie'),
                'response' => __('ProfilePress Order Factory class not found. Please ensure ProfilePress is properly installed.', 'dollie'),

            ];
        }

        $order_id = $selected_options['order_id'];
        $order = \ProfilePress\Core\Membership\Models\Order\OrderFactory::fromId(absint($order_id));

        return $order;
    }
}

GetOrderDetailsByID::get_instance();
