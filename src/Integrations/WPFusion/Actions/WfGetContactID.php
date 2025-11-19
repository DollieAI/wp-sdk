<?php

namespace Dollie\SDK\Integrations\WPFusion\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'wf_get_contact_id',
    label: 'Get Contact ID',
    since: '1.0.0'
)]
/**
 * WfGetContactID.
 * php version 5.6
 *
 * @category WfGetContactID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WfGetContactID
 *
 * @category WfGetContactID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WfGetContactID extends AutomateAction
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
    public $action = 'wf_get_contact_id';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get Contact ID', 'dollie'),
            'action' => 'wf_get_contact_id',
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
        $user_email = $selected_options['wp_user_email'];

        if (! function_exists('wp_fusion')) {
            return false;
        }
        $wpfusion_api = wp_fusion()->crm->get_contact_id($user_email);
        $error_message = '';

        if (is_wp_error($wpfusion_api)) {
            $error_message = $wpfusion_api->get_error_message();
            $response = [
                'status' => 'error',
                'message' => $error_message,
            ];
        } else {
            if ($wpfusion_api) {
                $response = [
                    'status' => 'success',
                    'contact_id' => $wpfusion_api,
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => esc_html__('No contact found for the given email', 'dollie'),

                ];
            }
        }

        return $response;
    }
}

WfGetContactID::get_instance();
