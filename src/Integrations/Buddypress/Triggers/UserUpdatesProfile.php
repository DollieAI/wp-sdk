<?php

namespace Dollie\SDK\Integrations\Buddypress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_updates_profile',
    label: 'A user updates their profile',
    since: '1.0.0'
)]
/**
 * UserUpdatesProfile.
 * php version 5.6
 *
 * @category UserUpdatesProfile
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserUpdatesProfile
 *
 * @category UserUpdatesProfile
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserUpdatesProfile
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BuddyPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_updates_profile';

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
            'label' => __('A user updates their profile', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'xprofile_updated_profile',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 5,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $user_id User ID.
     * @param array $posted_field_ids Posted Field IDs.
     * @param bool  $errors Errors.
     * @param array $old_values Old Values.
     * @param array $new_values New Values.
     * @return void
     */
    public function trigger_listener($user_id, $posted_field_ids, $errors, $old_values, $new_values)
    {

        foreach ($posted_field_ids as $field_id) {
            if (function_exists('xprofile_get_field')) {
                $field = xprofile_get_field($field_id);
                $context[$field->name] = $new_values;
            }
        }
        $context['user'] = WordPress::get_user_context($user_id);
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
UserUpdatesProfile::get_instance();
