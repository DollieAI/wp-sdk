<?php

namespace Dollie\SDK\Integrations\Lifterlms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'lifterlms_purchase_course',
    label: 'User purchase course',
    since: '1.0.0'
)]
/**
 * PurchaseCourse.
 * php version 5.6
 *
 * @category PurchaseCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PurchaseCourse
 *
 * @category PurchaseCourse
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class PurchaseCourse
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'LifterLMS';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'lifterlms_purchase_course';

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
            'label' => __('User purchase course', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'lifterlms_order_complete',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener.
     *
     * @param int $order_id order id.
     * @return void
     */
    public function trigger_listener($order_id)
    {

        $user_id = get_post_meta($order_id, '_llms_user_id', true);
        $context['course_id'] = get_post_meta($order_id, '_llms_product_id', true);
        $context['course_name'] = get_post_meta($order_id, '_llms_product_title', true);
        $context['course_amount'] = get_post_meta($order_id, '_llms_original_total', true);
        $context['currency'] = get_post_meta($order_id, '_llms_currency', true);
        $context['order'] = WordPress::get_post_context($order_id);
        $context['order_type'] = get_post_meta($order_id, '_llms_order_type', true);
        $context['trial_offer'] = get_post_meta($order_id, '_llms_trial_offer', true);
        $context['billing_frequency'] = get_post_meta($order_id, '_llms_billing_frequency', true);

        /**
         * User ID.
         *
         * @var string $user_id
         */
        $context = array_merge($context, WordPress::get_user_context(intval($user_id)));

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );

    }
}

PurchaseCourse::get_instance();
