<?php

namespace Dollie\SDK\Integrations\Dollie\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\Dollie\Dollie;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

/**
 * DeleteSite Action
 */
#[Action(
    id: 'dollie_delete_site',
    label: 'Delete Site',
    since: '1.0.0'
)]

/**
 * Class DeleteSite
 */
class DeleteSite extends AutomateAction
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
    public $action = 'dollie_delete_site';

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
            'label' => __('Delete Site', 'dollie'),
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
        // Validate required fields
        $site_id = isset($selected_options['site_id']) ? absint($selected_options['site_id']) : 0;

        if (empty($site_id)) {
            return [
                'status' => 'error',
                'message' => __('Site ID is required.', 'dollie'),
            ];
        }

        // Verify site exists and is a container
        $site = get_post($site_id);

        if (! $site || $site->post_type !== 'container') {
            return [
                'status' => 'error',
                'message' => __('Invalid site ID or site does not exist.', 'dollie'),
            ];
        }

        // Get site context before deletion
        $site_context = Dollie::get_site_context($site_id);

        // Delete site
        $deleted = wp_delete_post($site_id, true);

        if (! $deleted) {
            return [
                'status' => 'error',
                'message' => __('Failed to delete site.', 'dollie'),
            ];
        }

        // Get context
        $context = Dollie::get_user_context($user_id);
        $context = array_merge($context, $site_context);

        $context['status'] = 'success';
        $context['message'] = __('Site deleted successfully.', 'dollie');

        return $context;
    }
}

DeleteSite::get_instance();
