<?php

namespace Dollie\SDK\Integrations\Wordpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

/**
 * UserCreate.
 * php version 5.6
 *
 * @category UserCreate
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
#[Trigger(
    id: 'wordpress.user.created',
    label: 'User is created',
    description: 'Fires when a new WordPress user is registered',
    payloadSchema: [
        'type' => 'object',
        'properties' => [
            'wp_user_id' => ['type' => 'integer', 'description' => 'User ID'],
            'user_login' => ['type' => 'string', 'description' => 'Username'],
            'display_name' => ['type' => 'string', 'description' => 'Display name'],
            'user_firstname' => ['type' => 'string', 'description' => 'First name'],
            'user_lastname' => ['type' => 'string', 'description' => 'Last name'],
            'user_email' => ['type' => 'string', 'format' => 'email', 'description' => 'Email address'],
            'user_registered' => ['type' => 'string', 'format' => 'date-time', 'description' => 'Registration date'],
            'user_role' => ['type' => 'array', 'items' => ['type' => 'string'], 'description' => 'User roles']
        ]
    ],
    examples: [[
        'wp_user_id' => 123,
        'user_login' => 'johndoe',
        'display_name' => 'John Doe',
        'user_firstname' => 'John',
        'user_lastname' => 'Doe',
        'user_email' => 'john@example.com',
        'user_registered' => '2025-11-12 10:30:00',
        'user_role' => ['subscriber']
    ]],
    tags: ['users', 'registration'],
    since: '1.0.0'
)]
class UserCreate
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
    public $trigger = 'user_register';

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
            'label' => __('User is created', 'dollie'),
            'action' => 'user_register',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int $user_id created user id.
     *
     * @return void
     */
    public function trigger_listener($user_id)
    {

        $context = WordPress::get_user_context($user_id);
        $user = get_userdata($user_id);

        if ($user) {
            $display_name = $user->display_name;
            $display_name = explode(' ', $display_name);
            if (! empty($display_name)) {
                if ('' != $display_name[0]) {
                    $context['user_firstname'] = $display_name[0];
                }
                if (array_key_exists(1, $display_name) && '' != $display_name[1]) {
                    $context['user_lastname'] = $display_name[1];
                }
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


UserCreate::get_instance();
