<?php

namespace Dollie\SDK\Integrations\Fluentcommunity\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentCommunity\App\Services\Helper;

#[Action(
    id: 'fc_remove_user_from_space',
    label: 'Remove User from Space',
    since: '1.0.0'
)]
/**
 * RemoveUserFromSpace
 *
 * @category RemoveUserFromSpace
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * Class RemoveUserFromSpace
 *
 * @category RemoveUserFromSpace
 * @author   BSF <username@example.com>
 * @since    1.0.0
 */
class RemoveUserFromSpace extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentCommunity';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'fc_remove_user_from_space';

    /**
     * Register the action.
     *
     * @param array $actions Actions array.
     *
     * @return array Modified actions array.
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Remove User from Space', 'dollie'),
            'action' => $this->action,
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener for removing user from space.
     *
     * @param int   $user_id        User ID.
     * @param int   $automation_id  Automation ID.
     * @param array $fields         Fields data.
     * @param array $selected_options Selected options.
     *
     * @return array|void Status and message.
     *
     * @throws Exception If removal fails.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $space_id = isset($selected_options['space_id']) ? (int) sanitize_text_field($selected_options['space_id']) : 0;
        $user_email = isset($selected_options['user_email']) ? sanitize_email($selected_options['user_email']) : '';
        $by = 'by_automation';

        if (empty($space_id) || ! $this->is_valid_space_id($space_id)) {
            return [
                'status' => 'error',
                'message' => 'Invalid space ID.',
            ];
        }

        $user = get_user_by('email', $user_email);

        if (! $user) {
            return [
                'status' => 'error',
                'message' => 'User not found with the provided email.',
            ];
        }

        if (! class_exists('FluentCommunity\App\Services\Helper')) {
            return [
                'status' => 'error',
                'message' => 'Helper class not found.',
            ];
        }

        try {
            Helper::removeFromSpace($space_id, $user->ID, $by);

            return [
                'status' => 'success',
                'message' => 'User removed from space successfully',
                'space_id' => $space_id,
                'user_id' => $user->ID,
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Error removing user from space: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Validate space ID.
     *
     * @param int $space_id Space ID.
     *
     * @return bool Whether space ID is valid.
     */
    private function is_valid_space_id($space_id)
    {
        global $wpdb;

        // Directly prepare the query and pass to get_var.
        return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}fcom_spaces WHERE ID = %d", $space_id)) > 0;
    }
}

RemoveUserFromSpace::get_instance();
