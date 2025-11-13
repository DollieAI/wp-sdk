<?php

namespace Dollie\SDK\Integrations\Voxel\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'voxel_update_post_field',
    label: 'Update Voxel Post Field',
    since: '1.0.0'
)]
/**
 * UpdateVoxelPostField.
 * php version 5.6
 *
 * @category UpdateVoxelPostField
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UpdateVoxelPostField
 *
 * @category UpdateVoxelPostField
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UpdateVoxelPostField extends AutomateAction
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
    public $action = 'voxel_update_post_field';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Update Voxel Post Field', 'dollie'),
            'action' => 'voxel_update_post_field',
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
        if (! class_exists('Voxel\Post')) {
            return [
                'success' => false,
                'message' => esc_attr__('Voxel classes not found', 'dollie'),
            ];
        }

        $post_id = isset($selected_options['post_id']) ? absint($selected_options['post_id']) : 0;
        if (! $post_id) {
            return [
                'success' => false,
                'message' => esc_attr__('Post ID is required', 'dollie'),
            ];
        }

        $post = get_post($post_id);
        if (! $post) {
            return [
                'success' => false,
                'message' => esc_attr__('Post not found', 'dollie'),
            ];
        }

        $field_type = isset($selected_options['field_type']) ? sanitize_text_field($selected_options['field_type']) : '';

        if ('wp_field' === $field_type) {
            $field_name = isset($selected_options['field_name']) ? sanitize_text_field($selected_options['field_name']) : '';
            $field_value = isset($selected_options['field_value']) ? $selected_options['field_value'] : '';

            if (empty($field_name)) {
                return [
                    'success' => false,
                    'message' => esc_attr__('Field name is required', 'dollie'),
                ];
            }

            return $this->update_wordpress_field($post_id, $field_name, $field_value);

        } elseif ('custom_field' === $field_type) {
            $post_type = get_post_type($post_id);
            if (false === $post_type) {
                return [
                    'success' => false,
                    'message' => esc_attr__('Invalid post type', 'dollie'),
                ];
            }
            $post_fields = [];

            if (isset($selected_options['field_row']) && is_array($selected_options['field_row'])) {
                foreach ($selected_options['field_row'] as $field) {
                    if (isset($field['field_column_name'])) {
                        $field_name = sanitize_text_field($field['field_column_name']);

                        if (isset($field[$field_name]) && ! empty($field[$field_name])) {
                            $post_fields[$field_name] = $field[$field_name];
                        }
                    }
                }
            }

            if (empty($post_fields)) {
                return [
                    'success' => false,
                    'message' => esc_attr__('No custom field data provided to update', 'dollie'),
                ];
            }

            $result = \SureTriggers\Integrations\Voxel\Voxel::voxel_update_post($post_fields, $post_id, $post_type);

            if (true === $result) {
                return [
                    'success' => true,
                    'message' => esc_attr__('Voxel custom fields updated successfully', 'dollie'),
                    'post_id' => $post_id,
                    'post_url' => get_permalink($post_id),
                ];
            } else {
                return [
                    'success' => false,
                    'message' => esc_attr__('Failed to update Voxel custom fields', 'dollie'),
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => esc_attr__('Invalid field type', 'dollie'),
            ];
        }
    }

    /**
     * Update WordPress standard field.
     *
     * @param int    $post_id Post ID.
     * @param string $field_name Field name.
     * @param mixed  $field_value Field value.
     * @return array
     */
    private function update_wordpress_field($post_id, $field_name, $field_value)
    {
        $update_data = ['ID' => $post_id];

        switch ($field_name) {
            case 'post_title':
                if (is_string($field_value) || is_numeric($field_value)) {
                    $update_data['post_title'] = sanitize_text_field((string) $field_value);
                }
                break;
            case 'post_content':
                if (is_string($field_value) || is_numeric($field_value)) {
                    $update_data['post_content'] = wp_kses_post((string) $field_value);
                }
                break;
            case 'post_excerpt':
                if (is_string($field_value) || is_numeric($field_value)) {
                    $update_data['post_excerpt'] = sanitize_textarea_field((string) $field_value);
                }
                break;
            case 'post_status':
                if (is_string($field_value) || is_numeric($field_value)) {
                    $valid_statuses = get_post_stati();
                    $field_value_string = (string) $field_value;
                    if (in_array($field_value_string, array_keys($valid_statuses))) {
                        $update_data['post_status'] = $field_value_string;
                    } else {
                        return [
                            'success' => false,
                            'message' => esc_attr__('Invalid post status', 'dollie'),
                        ];
                    }
                }
                break;
            case 'post_slug':
                if (is_string($field_value) || is_numeric($field_value)) {
                    $update_data['post_name'] = sanitize_title((string) $field_value);
                }
                break;
            case 'post_date':
                if (is_string($field_value) || is_numeric($field_value)) {
                    $date = sanitize_text_field((string) $field_value);
                    if (strtotime($date)) {
                        $update_data['post_date'] = $date;
                    } else {
                        return [
                            'success' => false,
                            'message' => esc_attr__('Invalid date format', 'dollie'),
                        ];
                    }
                }
                break;
            case 'featured_image':
                if (is_string($field_value) || is_numeric($field_value)) {
                    $image_id = absint((string) $field_value);
                    if ($image_id && wp_attachment_is_image($image_id)) {
                        set_post_thumbnail($post_id, $image_id);

                        return [
                            'success' => true,
                            'message' => esc_attr__('Featured image updated successfully', 'dollie'),
                            'post_id' => $post_id,
                            'field_name' => $field_name,
                        ];
                    } else {
                        return [
                            'success' => false,
                            'message' => esc_attr__('Invalid image ID', 'dollie'),
                        ];
                    }
                }
                break;
            default:
                update_post_meta($post_id, $field_name, $field_value);

                return [
                    'success' => true,
                    'message' => esc_attr__('Post meta updated successfully', 'dollie'),
                    'post_id' => $post_id,
                    'field_name' => $field_name,
                ];
        }

        $result = wp_update_post($update_data);

        if (0 === $result) {
            return [
                'success' => false,
                'message' => esc_attr__('Failed to update post: Unknown error', 'dollie'),
            ];
        }

        return [
            'success' => true,
            'message' => esc_attr__('WordPress field updated successfully', 'dollie'),
            'post_id' => $post_id,
            'field_name' => $field_name,
        ];
    }
}

UpdateVoxelPostField::get_instance();
