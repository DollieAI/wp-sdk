<?php

namespace Dollie\SDK\Integrations\Gamipress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_attains_rank',
    label: 'A user attains a rank',
    since: '1.0.0'
)]
/**
 * UserAttainsRank.
 * php version 5.6
 *
 * @category UserAttainsRank
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserAttainsRank
 *
 * @category UserAttainsRank
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserAttainsRank
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
    public $trigger = 'user_attains_rank';

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
            'label' => __('A user attains a rank', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'gamipress_update_user_rank',
            'function' => [$this, 'trigger_listener'],
            'priority' => 20,
            'accepted_args' => 5,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $user_id User ID.
     * @param object $new_rank New Rank.
     * @param object $old_rank Old Rank.
     * @param string $admin_id Admin ID.
     * @param string $achievement_id Achivement ID.
     * @return void
     */
    public function trigger_listener($user_id, $new_rank, $old_rank, $admin_id, $achievement_id)
    {
        if (property_exists($new_rank, 'ID')) {
            $data = WordPress::get_post_context($new_rank->ID);
            $context = array_merge($data, WordPress::get_user_context($user_id));
            $context['rank'] = $new_rank->ID;
            if (property_exists($new_rank, 'post_type')) {
                $context['rank_type'] = $new_rank->post_type;
            }
            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
UserAttainsRank::get_instance();
