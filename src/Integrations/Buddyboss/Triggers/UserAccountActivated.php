<?php

namespace Dollie\SDK\Integrations\Buddyboss\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'bb_user_account_activated',
    label: 'User Account Activated',
    since: '1.0.0'
)]
/**
 * UserAccountActivated.
 * php version 5.6
 *
 * @category UserAccountActivated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserAccountActivated
 *
 * @category UserAccountActivated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserAccountActivated
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
    public $trigger = 'bb_user_account_activated';

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
            'label' => __('User Account Activated', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'bp_core_activated_user',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     *  Trigger listener
     *
     * @param int   $user_id user id.
     * @param int   $key Key.
     * @param array $user user.
     *
     * @return void
     */
    public function trigger_listener($user_id, $key, $user)
    {

        if (empty($user)) {
            return;
        }
        global $wpdb;

        $signups = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}signups 
			WHERE active = 1 AND activation_key = %s ORDER BY signup_id DESC LIMIT 1",
                $key
            )
        );

        $context = $signups[0];
        $context = get_object_vars($context);
        unset($context['activation_key']);
        if (is_string($context['meta'])) {
            $context['meta'] = unserialize($context['meta']);
        }
        if (is_array($context['meta'])) {
            unset($context['meta']['password']);
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

UserAccountActivated::get_instance();
