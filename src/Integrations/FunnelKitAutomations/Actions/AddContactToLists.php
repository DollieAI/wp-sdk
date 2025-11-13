<?php

namespace Dollie\SDK\Integrations\FunnelKitAutomations\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\FunnelKitAutomations\FunnelKitAutomations;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'funnel_kit_automations_add_contact_to_lists',
    label: 'Add Contact to List(s)',
    since: '1.0.0'
)]
/**
 * AddContactToLists.
 * php version 5.6
 *
 * @category AddContactToLists
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddContactToLists
 *
 * @category AddContactToLists
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddContactToLists extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FunnelKitAutomations';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'funnel_kit_automations_add_contact_to_lists';

    /**
     * Register an action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Add Contact to List(s)', 'dollie'),
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
     * @param array $selected_options selected_options.
     *
     * @return array
     *
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! class_exists('BWFCRM_Contact')) {
            return [
                'status' => 'error',
                'message' => 'Plugin not installed correctly.',
            ];
        }

        $email = sanitize_email($selected_options['contact_email']);

        if (! is_email($email)) {
            return [
                'status' => 'error',
                'message' => 'Invalid email.',
            ];
        }

        $list_ids = $selected_options['list_ids'];

        $lists_to_add = [];
        foreach ($list_ids as $list) {
            $lists_to_add[] = ['id' => $list['value']];
        }

        $bwfcm_contact = new \BWFCRM_Contact($email);

        $result = $bwfcm_contact->add_lists($lists_to_add);

        if (is_wp_error($result)) {
            throw new Exception($result->get_error_message());
        }

        $bwfcm_contact->save();

        return FunnelKitAutomations::get_contact_context($bwfcm_contact->contact);
    }
}

AddContactToLists::get_instance();
