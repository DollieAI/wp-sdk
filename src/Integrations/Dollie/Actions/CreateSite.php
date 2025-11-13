<?php

namespace Dollie\SDK\Integrations\Dollie\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\Dollie\Dollie;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

/**
 * CreateSite Action
 */
#[Action(
    id: 'dollie_create_site',
    label: 'Create Site',
    since: '1.0.0'
)]

/**
 * Class CreateSite
 */
class CreateSite extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Dollie';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'dollie_create_site';

    /**
     * Register action.
     *
     * @param array $actions actions.
     *
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Create Site', 'dollie'),
            'action' => $this->action,
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
     * @return array
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        // Check if user email is provided
        if (! isset($selected_options['wp_user_email'])) {
            return [
                'status' => 'error',
                'message' => __('User email is required.', 'dollie'),
            ];
        }

        // Get user ID from email
        $user_id = email_exists($selected_options['wp_user_email']);

        if (false === $user_id) {
            return [
                'status' => 'error',
                'message' => __('User with email does not exist: ', 'dollie') . $selected_options['wp_user_email'],
            ];
        }

        // Validate required fields
        $site_name = isset($selected_options['site_name']) ? sanitize_text_field($selected_options['site_name']) : '';
        $blueprint_id = isset($selected_options['blueprint_id']) ? absint($selected_options['blueprint_id']) : 0;

        if (empty($site_name)) {
            return [
                'status' => 'error',
                'message' => __('Site name is required.', 'dollie'),
            ];
        }

        // Create site using Dollie API
        // Note: This is a placeholder - you'll need to implement actual Dollie site creation logic
        $site_data = [
            'post_title' => $site_name,
            'post_type' => 'container',
            'post_status' => 'publish',
            'post_author' => $user_id,
        ];

        $site_id = wp_insert_post($site_data);

        if (is_wp_error($site_id)) {
            return [
                'status' => 'error',
                'message' => $site_id->get_error_message(),
            ];
        }

        // If blueprint ID is provided, associate it
        if ($blueprint_id) {
            update_post_meta($site_id, 'wpd_blueprint_id', $blueprint_id);
        }

        // Get context
        $context = Dollie::get_user_context($user_id);
        $site_context = Dollie::get_site_context($site_id);
        $context = array_merge($context, $site_context);

        $context['status'] = 'success';
        $context['message'] = __('Site created successfully.', 'dollie');

        return $context;
    }
}

CreateSite::get_instance();
