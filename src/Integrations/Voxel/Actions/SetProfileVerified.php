<?php

namespace Dollie\SDK\Integrations\Voxel\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'voxel_set_profile_verified',
    label: 'Send Email',
    since: '1.0.0'
)]
/**
 * SetProfileVerified.
 * php version 5.6
 *
 * @category SetProfileVerified
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SetProfileVerified
 *
 * @category SetProfileVerified
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class SetProfileVerified extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Voxel';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'voxel_set_profile_verified';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Send Email', 'dollie'),
            'action' => 'voxel_set_profile_verified',
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user_id.
     * @param int   $automation_id automation_id.
     * @param array $fields fields.
     * @param array $selected_options selectedOptions.
     *
     * @throws Exception Exception.
     *
     * @return bool|array
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $profile_id = $selected_options['profile_id'];

        if (! class_exists('Voxel\Post')) {
            return false;
        }

        $post = \Voxel\Post::force_get($profile_id);

        if (! $post) {
            return [
                'status' => 'error',
                'message' => 'Profile not found',
            ];
        }

        // Set the post as verified.
        $post->set_verified(true);

        return [
            'success' => true,
            'message' => esc_attr__('Profile Verified Successfully', 'dollie'),

        ];
    }
}

SetProfileVerified::get_instance();
