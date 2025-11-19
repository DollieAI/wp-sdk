<?php

namespace Dollie\SDK\Integrations\WPFusion\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'wf_add_tag_to_user',
    label: 'Add Tag To User',
    since: '1.0.0'
)]
/**
 * WfAddTagToUser.
 * php version 5.6
 *
 * @category WfAddTagToUser
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WfAddTagToUser
 *
 * @category WfAddTagToUser
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WfAddTagToUser extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPFusion';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'wf_add_tag_to_user';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Add Tag To User', 'dollie'),
            'action' => 'wf_add_tag_to_user',
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user ID.
     * @param int   $automation_id automation ID.
     * @param array $fields fields.
     * @param array $selected_options selected options.
     *
     * @throws Exception Exception.
     *
     * @return bool|array
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $tag = $selected_options['tag'];
        $user_id = $selected_options['wp_user_email'];

        if (! function_exists('wp_fusion')) {
            return false;
        }

        if (is_email($user_id)) {
            $user = get_user_by('email', $user_id);

            if ($user) {
                $user_id = $user->ID;
                $contact_id = wp_fusion()->user->get_contact_id($user_id, true);

                if (false === $contact_id) {
                    wp_fusion()->user->user_register($user_id);
                }

                $tag_name = $tag;
                $tag_id = wp_fusion()->user->get_tag_id($tag_name);

                if (false === $tag_id) {
                    // Tag doesn't exist, create a new tag.

                    if (in_array('add_tags_api', wp_fusion()->crm->supports, true) || (is_object(wp_fusion()->crm) && method_exists(wp_fusion()->crm, 'add_tag'))) {
                        $new_tag_id = wp_fusion()->crm->add_tag($tag_name);

                        if (! empty($new_tag_id)) {
                            $tag_id = $new_tag_id;
                        } else {
                            return [
                                'status' => esc_attr__('Error', 'dollie'),
                                'response' => esc_attr__('Failed to create new tag.', 'dollie'),

                            ];
                        }
                    } else {
                        return [
                            'status' => esc_attr__('Error', 'dollie'),
                            'response' => esc_attr__('WP Fusion CRM does not support tag creation.', 'dollie'),

                        ];
                    }
                }

                $current_tags = wp_fusion()->user->get_tags($user_id);


                if (! in_array($tag_id, $current_tags, true)) {
                    wp_fusion()->user->apply_tags([$tag_id], $user_id);
                }

                $existing_tags = wp_fusion()->user->get_tags($user_id, true);

                $updated_tags = array_unique(array_merge($existing_tags, [$tag_id]));

                // Get all available tags from WPFusion.
                wp_fusion()->user->set_tags($updated_tags, $user_id);
                $all_tags = wp_fusion()->settings->get('available_tags');

                if (! isset($all_tags[$tag_id])) {
                    $all_tags[$tag_id] = $tag_name;
                    wp_fusion()->settings->set('available_tags', $all_tags);
                    update_option('wpf_settings', wp_fusion()->settings->get_all());
                }

                $tag_names = [];

                foreach ($updated_tags as $id) {
                    if (isset($all_tags[$id])) {
                        $tag_names[] = $all_tags[$id];
                    }
                }

                return [
                    'tags' => $tag_names,
                    'user_context' => WordPress::get_user_context($user_id),
                ];
            } else {
                return [
                    'status' => esc_attr__('Error', 'dollie'),
                    'response' => esc_attr__('Please enter valid user.', 'dollie'),

                ];
            }
        } else {
            return [
                'status' => esc_attr__('Error', 'dollie'),
                'response' => esc_attr__('Please enter valid email address.', 'dollie'),

            ];
        }
    }
}

WfAddTagToUser::get_instance();
