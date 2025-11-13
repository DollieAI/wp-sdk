<?php

namespace Dollie\SDK\Integrations\Wployalty\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wp_loyalty_points_awarded_customer',
    label: 'Points Awarded Customer',
    since: '1.0.0'
)]
/**
 * WPLoyaltyPointsAwardedCustomer.
 * php version 5.6
 *
 * @category WPLoyaltyPointsAwardedCustomer
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WPLoyaltyPointsAwardedCustomer
 *
 * @category WPLoyaltyPointsAwardedCustomer
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class WPLoyaltyPointsAwardedCustomer
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPLoyalty';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wp_loyalty_points_awarded_customer';

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
            'label' => __('Points Awarded Customer', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wlr_after_add_earn_point',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param string $user_email User Email.
     * @param int    $point Point.
     * @param string $action_type Action Type.
     * @param array  $action_data Action Data.
     * @return void
     */
    public function trigger_listener($user_email, $point, $action_type, $action_data)
    {
        global $wpdb;

        if (! class_exists('Wlr\App\Helpers\Base')) {
            return;
        }
        $context['user_email'] = $user_email;
        $context['points_earned'] = $point;
        $context['action_type'] = $action_type;
        $base_helper = new \Wlr\App\Helpers\Base();
        $user = $base_helper->getPointUserByEmail($user_email);
        $points_sql = 'SELECT * FROM ' . $wpdb->prefix . 'wlr_expire_points 
				WHERE user_email = %s ORDER BY id DESC LIMIT 1';
        $points_results = $wpdb->get_results(
            $wpdb->prepare(
                $points_sql,
                $user_email
            ),
            ARRAY_A
        ); // @phpcs:ignore
        $context['user'] = $user;
        if (! empty($points_results)) {
            $expire_date = $points_results[0]['expire_date'];
            $timestamp = is_numeric($expire_date) ? (int) $expire_date : null;
            $date_format = get_option('date_format');
            if (is_string($date_format)) {
                $context['point_expiry_date'] = wp_date($date_format, $timestamp);
            }
        }

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
WPLoyaltyPointsAwardedCustomer::get_instance();
