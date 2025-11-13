<?php

namespace Dollie\SDK\Integrations\WPUserManager\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wpuser_manager_user_updates_cover_photo',
    label: 'User Updates Cover Photo',
    since: '1.0.0'
)]
/**
 * UserUpdatesCoverPhoto.
 * php version 5.6
 *
 * @category UserUpdatesCoverPhoto
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserUpdatesCoverPhoto
 *
 * @category UserUpdatesCoverPhoto
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserUpdatesCoverPhoto
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
    public $trigger = 'wpuser_manager_user_updates_cover_photo';

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
            'label' => __('User Updates Cover Photo', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wpum_user_update_change_cover',
            'function' => [$this, 'trigger_listener'],
            'priority' => 99,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $user_id User ID.
     * @param string $value    Value.
     *
     * @return void
     */
    public function trigger_listener($user_id, $value)
    {
        if (0 === absint($user_id)) {
            return;
        }

        $context['user_id'] = WordPress::get_user_context($user_id);
        $context['cover_photo'] = $value;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $user_id,
                'context' => $context,
            ]
        );
    }
}

UserUpdatesCoverPhoto::get_instance();
