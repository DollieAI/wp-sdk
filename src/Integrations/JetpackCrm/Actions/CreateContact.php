<?php

namespace Dollie\SDK\Integrations\JetpackCRM\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\JetpackCRM\JetpackCRM;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'jetpack_crm_create_contact',
    label: 'Create Contact',
    since: '1.0.0'
)]
/**
 * CreateContact.
 * php version 5.6
 *
 * @category CreateContact
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateContact
 *
 * @category CreateContact
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateContact extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'JetpackCRM';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'jetpack_crm_create_contact';

    /**
     * Register an action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Create Contact', 'dollie'),
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
        $contact_details = [];

        foreach ($selected_options as $label => $value) {
            if ('runningTestAction' === $label) {
                continue;
            }

            if ('tags' === $label || 'companies' === $label) {
                $contact_details['data'][$label] = ! empty($value) ? [sanitize_text_field($value)] : [];
            } else {
                $contact_details['data'][$label] = ! empty($value) ? sanitize_text_field($value) : '';
            }
        }

        global $zbs;
        $contact_id = $zbs->DAL->contacts->addUpdateContact($contact_details); // phpcs:ignore

        if (! $contact_id) {
            return [
                'status' => 'error',
                'message' => 'Something went wrong while creating contact.',
            ];
        }

        return JetpackCRM::get_contact_context($contact_id);
    }
}

CreateContact::get_instance();
