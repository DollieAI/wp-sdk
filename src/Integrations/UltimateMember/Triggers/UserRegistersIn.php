<?php

namespace Dollie\SDK\Integrations\UltimateMember\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_registers_form',
    label: 'User Registers With A Form',
    since: '1.0.0'
)]
/**
 * UserRegistersIn.
 * php version 5.6
 *
 * @category UserRegistersIn
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserRegistersIn
 *
 * @category UserRegistersIn
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserRegistersIn
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'UltimateMember';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_registers_form';

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
            'label' => __('User Registers With A Form', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'um_registration_complete',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $user_id User ID.
     * @param array $um_args arguments.
     * @return void
     */
    public function trigger_listener($user_id, $um_args)
    {

        if (! isset($um_args['form_id'])) {
            return;
        }
        if (is_array($um_args) && isset($um_args['submitted'])) {
            unset(
                $um_args['submitted']['user_password'],
                $um_args['submitted']['confirm_user_password']
            );
        }

        $data = WordPress::get_user_context($user_id);
        $data['data'] = $um_args['submitted'];
        $data['form_id'] = absint($um_args['form_id']);

        $context = $data;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'user_id' => $user_id,
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
UserRegistersIn::get_instance();
