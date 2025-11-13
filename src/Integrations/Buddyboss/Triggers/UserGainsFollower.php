<?php

namespace Dollie\SDK\Integrations\Buddyboss\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'bb_user_gains_follower',
    label: 'User Gains Follower',
    since: '1.0.0'
)]
/**
 * UserGainsFollower.
 * php version 5.6
 *
 * @category UserGainsFollower
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserGainsFollower
 *
 * @category UserGainsFollower
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserGainsFollower
{
    use SingletonLoader;

    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BuddyBoss';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'bb_user_gains_follower';

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
     * @param array $triggers triggers.
     *
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('User Gains Follower', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'bp_start_following',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     *  Trigger listener
     *
     * @param object $follow Folow.
     *
     * @return void
     */
    public function trigger_listener($follow)
    {

        if (is_object($follow) && property_exists($follow, 'follower_id') && property_exists($follow, 'leader_id')) {
            $context['follower'] = WordPress::get_user_context($follow->follower_id);
            $context['leader'] = WordPress::get_user_context($follow->leader_id);
            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

UserGainsFollower::get_instance();
