<?php

namespace Dollie\SDK\Integrations\Voxel\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\Voxel\Voxel;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'voxel_profile_new_wall_post',
    label: 'Profile New Wall Post',
    since: '1.0.0'
)]
/**
 * ProfileWallNewPost.
 * php version 5.6
 *
 * @category ProfileWallNewPost
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ProfileWallNewPost
 *
 * @category ProfileWallNewPost
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class ProfileWallNewPost
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Voxel';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'voxel_profile_new_wall_post';

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
            'label' => __('Profile New Wall Post', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'voxel/app-events/post-types/profile/wall-post:created',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $event Event.
     * @return void
     */
    public function trigger_listener($event)
    {
        if (! property_exists($event, 'status') || ! property_exists($event, 'author')) {
            return;
        }
        $context['profile'] = Voxel::get_post_fields($event->status->get_post_id());
        $user = get_userdata($event->author->get_id());
        if ($user) {
            $user_data = (array) $user->data;
            $context['profile_display_name'] = $user_data['display_name'];
            $context['profile_name'] = $user_data['user_nicename'];
            $context['profile_email'] = $user_data['user_email'];
            $context['profile_user_id'] = $event->status->get_author();
        }
        if (class_exists('Voxel\Timeline\Status')) {
            // Get the status details.
            $status_details = \Voxel\Timeline\Status::get($event->status->get_id());
            foreach ((array) $status_details as $key => $value) {
                $clean_key = preg_replace('/^\0.*?\0/', '', $key);
                if (is_object($value)) {
                    $encoded_value = wp_json_encode($value);
                    if (is_string($encoded_value)) {
                        $value = json_decode($encoded_value, true);
                    }
                }
                $context['wall_post'][$clean_key] = $value;
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

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
ProfileWallNewPost::get_instance();
