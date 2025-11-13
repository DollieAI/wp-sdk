<?php

namespace Dollie\SDK\Integrations\WPUserManager\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wpuser_manager_user_updates_profile_description',
    label: 'User Updates Profile Description',
    since: '1.0.0'
)]
/**
 * UserUpdatesProfileDescription.
 * php version 5.6
 *
 * @category UserUpdatesProfileDescription
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserUpdatesProfileDescription
 *
 * @category UserUpdatesProfileDescription
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserUpdatesProfileDescription
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPUserManager';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wpuser_manager_user_updates_profile_description';

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
     *
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('User Updates Profile Description', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wpum_before_user_update',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $obj Object.
     * @param array  $values    Value.
     * @param int    $updated_id Updated ID.
     *
     * @return void
     */
    public function trigger_listener($obj, $values, $updated_id)
    {
        if (0 === absint($updated_id)) {
            return;
        }

        switch ($values['account']['user_displayname']) {
            case 'display_nickname':
                $name = $values['account']['user_nickname'];
                break;
            case 'display_firstname':
                $name = $values['account']['user_firstname'];
                break;
            case 'display_lastname':
                $name = $values['account']['user_lastname'];
                break;
            case 'display_firstlast':
                $name = $values['account']['user_firstname'] . ' ' . $values['account']['user_lastname'];
                break;
            case 'display_lastfirst':
                $name = $values['account']['user_lastname'] . ' ' . $values['account']['user_firstname'];
                break;
        }
        unset($values['account']['user_displayname']);
        $context['user'] = $values;

        /**
         * Ignore line
         *
         * @phpstan-ignore-next-line
         */
        $context['user_account_user_displayname'] = $name;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $updated_id,
                'context' => $context,
            ]
        );
    }
}

UserUpdatesProfileDescription::get_instance();
