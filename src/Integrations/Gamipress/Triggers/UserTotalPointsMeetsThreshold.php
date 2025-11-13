<?php

namespace Dollie\SDK\Integrations\Gamipress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_total_points_meets_threshold',
    label: 'User’s Total Point Meets A Threshold',
    since: '1.0.0'
)]
/**
 * UserTotalPointsMeetsThreshold.
 * php version 5.6
 *
 * @category UserTotalPointsMeetsThreshold
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserTotalPointsMeetsThreshold
 *
 * @category UserTotalPointsMeetsThreshold
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserTotalPointsMeetsThreshold
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'GamiPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_total_points_meets_threshold';

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
            'label' => __('User’s Total Point Meets A Threshold', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'gamipress_update_user_points',
            'function' => [$this, 'trigger_listener'],
            'priority' => 20,
            'accepted_args' => 8,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $user_id .
     * @param string $new_points .
     * @param string $total_points .
     * @param string $admin_id .
     * @param string $achievement_id .
     * @param string $points_type .
     * @param string $reason .
     * @param string $log_type .
     * @return void
     */
    public function trigger_listener($user_id, $new_points, $total_points, $admin_id, $achievement_id, $points_type, $reason, $log_type)
    {

        if (empty($user_id)) {
            return;
        }

        $data['new_points'] = $new_points;
        $data['total_points'] = $total_points;
        $data['points_type'] = $points_type;

        $post = get_page_by_path($points_type, OBJECT, 'points-type');

        $context = array_merge($data, WordPress::get_user_context($user_id));
        if (is_object($post)) {
            $context['point_type'] = $post->ID;
        }
        $context['point_threshold'] = $total_points;

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
UserTotalPointsMeetsThreshold::get_instance();
