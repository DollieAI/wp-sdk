<?php

namespace Dollie\SDK\Integrations\Projecthuddle\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'ph_create_mockup',
    label: 'Post: Create a Post',
    since: '1.0.0'
)]
/**
 * CreateMockup.
 * php version 5.6
 *
 * @category CreateMockup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateMockup
 *
 * @category CreateMockup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateMockup extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ProjectHuddle';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'ph_create_mockup';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Post: Create a Post', 'dollie'),
            'action' => 'ph_create_mockup',
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
     * @return bool|array
     * @throws Exception Error.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $result_arr = [];
        foreach ($fields as $field) {
            if (isset($field['name']) && isset($selected_options[$field['name']]) && (trim(wp_strip_all_tags($selected_options[$field['name']])) !== '')) {
                $result_arr[$field['name']] = $selected_options[$field['name']];
            }
        }
        // Set title as post_name.
        $result_arr['post_title'] = $selected_options['post_name'];

        // Set post_status as publish.
        $result_arr['post_status'] = 'publish';

        // Create for Mockup post type.
        $result_arr['post_type'] = 'ph-project';

        $post_id = wp_insert_post($result_arr);

        if (! $post_id) {
            return [
                'status' => 'error',
                'message' => 'Failed to insert mockup!',
            ];
        }
        $project_access_link = get_post_meta($post_id, 'access_token', true);

        $post = WordPress::get_post_context($post_id);
        $permalink = get_the_permalink($post_id);
        $post['project_access_link'] = $permalink . '?access_token=' . $project_access_link;

        return $post;
    }
}

CreateMockup::get_instance();
