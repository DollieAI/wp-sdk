<?php

namespace Dollie\SDK\Integrations\Wordpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wp_login',
    label: 'User logs in to the site',
    since: '1.0.0'
)]
/**
 * UserLogin.
 * php version 5.6
 *
 * @category UserLogin
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserLogin
 *
 * @category UserLogin
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserLogin
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WordPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wp_login';

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
            'label' => __('User logs in to the site', 'dollie'),
            'action' => 'wp_login',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array  $user_login user_login.
     * @param object $user user object.
     *
     * @return void
     */
    public function trigger_listener($user_login, $user)
    {
        if (! property_exists($user, 'ID')) {
            return;
        }
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => WordPress::get_user_context($user->ID),
            ]
        );

    }
}


UserLogin::get_instance();
