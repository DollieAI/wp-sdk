<?php

namespace Dollie\SDK\Integrations\Woocommerce\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'wc_retrieve_orders_totals',
    label: 'Retrieve Orders Totals',
    since: '1.0.0'
)]
/**
 * RetrieveOrdersTotals.
 * php version 5.6
 *
 * @category RetrieveOrdersTotals
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RetrieveOrdersTotals
 *
 * @category RetrieveOrdersTotals
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RetrieveOrdersTotals extends AutomateAction
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
    public $action = 'wc_retrieve_orders_totals';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Retrieve Orders Totals', 'dollie'),
            'action' => 'wc_retrieve_orders_totals',
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
        if (! class_exists('WooCommerce')) {
            return [
                'status' => 'error',
                'message' => __('WooCommerce is not installed or activated.', 'dollie'),

            ];
        }

        $order_statuses = [
            'pending' => 'Pending payment',
            'processing' => 'Processing',
            'on-hold' => 'On hold',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
            'failed' => 'Failed',
        ];

        $result = [];
        foreach ($order_statuses as $slug => $name) {
            $count = wc_orders_count($slug);
            $result[] = [
                'slug' => $slug,
                'name' => $name,
                'total' => $count,
            ];
        }

        return $result;
    }
}

RetrieveOrdersTotals::get_instance();
