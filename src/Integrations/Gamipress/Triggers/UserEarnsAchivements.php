<?php

namespace Dollie\SDK\Integrations\Gamipress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_earns_achivements',
    label: 'User Earns an Achievement',
    since: '1.0.0'
)]
/**
 * UserEarnsAchivements.
 * php version 5.6
 *
 * @category UserEarnsAchivements
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserEarnsAchivements
 *
 * @category UserEarnsAchivements
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserEarnsAchivements
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
    public $trigger = 'user_earns_achivements';

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
            'label' => __('User Earns an Achievement', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'gamipress_award_achievement',
            'function' => [$this, 'trigger_listener'],
            'priority' => 20,
            'accepted_args' => 5,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $user_id User ID.
     * @param int   $achievement_id Achivement ID.
     * @param array $trigger Trigger.
     * @param int   $site_id Site ID.
     * @param array $args Args.
     * @return void
     */
    public function trigger_listener($user_id, $achievement_id, $trigger, $site_id, $args)
    {

        if (empty($user_id)) {
            return;
        }

        $data = WordPress::get_post_context($achievement_id);

        $context = array_merge($data, WordPress::get_user_context($user_id));
        $context['achivement_type'] = $data['post_type'];
        $context['award'] = $data['ID'];
        $context['award_name'] = $data['post_title'];
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
UserEarnsAchivements::get_instance();
