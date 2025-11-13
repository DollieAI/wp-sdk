<?php

namespace Dollie\SDK\Integrations\Voxel\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'voxel_set_collection_post_verified',
    label: 'Set Collection Post Verified',
    since: '1.0.0'
)]
/**
 * SetCollectionPostVerified.
 * php version 5.6
 *
 * @category SetCollectionPostVerified
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SetCollectionPostVerified
 *
 * @category SetCollectionPostVerified
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class SetCollectionPostVerified extends AutomateAction
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
    public $action = 'voxel_set_collection_post_verified';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Set Collection Post Verified', 'dollie'),
            'action' => 'voxel_set_collection_post_verified',
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
        $post_id = $selected_options['post_id'];

        if (! class_exists('Voxel\Post')) {
            return false;
        }

        $post = \Voxel\Post::force_get($post_id);

        if (! $post) {
            return [
                'status' => 'error',
                'message' => 'Post not found',
            ];
        }

        // Set the post as verified.
        $post->set_verified(true);

        return [
            'success' => true,
            'message' => esc_attr__('Post Set as Verified', 'dollie'),

        ];
    }
}

SetCollectionPostVerified::get_instance();
