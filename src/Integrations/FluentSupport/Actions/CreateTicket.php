<?php

namespace Dollie\SDK\Integrations\FluentSupport\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentSupport\App\Api\Classes\Tickets;
use FluentSupport\App\Models\Ticket;

#[Action(
    id: 'create_ticket_fluent_support',
    label: 'Create Ticket',
    since: '1.0.0'
)]
/**
 * CreateTicket.
 * php version 5.6
 *
 * @category CreateTicket
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateTicket
 *
 * @category CreateTicket
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateTicket extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentSupport';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'create_ticket_fluent_support';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Create Ticket', 'dollie'),
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
     * @return array|void
     *
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $ticket_subject = sanitize_text_field($selected_options['ticket_subject']);
        $ticket_details = sanitize_text_field($selected_options['ticket_details']);
        $customer_id = sanitize_text_field($selected_options['customer_id']);
        $mailbox_id = $selected_options['mailbox_id'] ? sanitize_text_field($selected_options['mailbox_id']) : '';

        if (! class_exists('FluentSupport\App\Models\Ticket') || ! class_exists('FluentSupport\App\Api\Classes\Tickets')) {
            return [
                'status' => 'error',
                'message' => 'Error: Plugin did not installed correctly. Some classes are missing.',
            ];
        }

        $ticket = [
            'title' => $ticket_subject,
            'content' => $ticket_details,
            'customer_id' => $customer_id,
            'mailbox_id' => $mailbox_id,
        ];

        $ticket_cl = new Ticket();
        $tickets = new Tickets($ticket_cl);
        $response_data = $tickets->createTicket($ticket);

        if ($response_data) {
            return $response_data->getAttributes();
        }

        return [
            'status' => 'error',
            'message' => 'Failed to create ticket.',
        ];
    }
}

CreateTicket::get_instance();
