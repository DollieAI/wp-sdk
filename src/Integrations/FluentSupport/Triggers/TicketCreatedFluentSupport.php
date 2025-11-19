<?php

namespace Dollie\SDK\Integrations\FluentSupport\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use FluentSupport\App\Models\Ticket;

#[Trigger(
    id: 'ticket_created_fluent_support',
    label: 'Ticket Created',
    since: '1.0.0'
)]
/**
 * TicketCreatedFluentSupport.
 * php version 5.6
 *
 * @category TicketCreatedFluentSupport
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * TicketCreatedFluentSupport
 *
 * @category TicketCreatedFluentSupport
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class TicketCreatedFluentSupport
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentSupport';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ticket_created_fluent_support';

    /**
     * Constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register action.
     *
     * @param array $triggers trigger data.
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Ticket Created', 'dollie'),
            'action' => 'ticket_created_fluent_support',
            'common_action' => 'fluent_support/ticket_created',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $ticket ticket.
     * @param object $customer customer.
     *
     * @return void
     */
    public function trigger_listener($ticket, $customer)
    {

        $context = array_merge(
            [
                'ticket' => $ticket,
                'customer' => $customer,
            ]
        );
        if (property_exists($ticket, 'mailbox_id')) {
            $context['mailbox_id'] = $ticket->mailbox_id;
        }

        if (! class_exists('\FluentSupport\App\Models\Ticket')) {
            return;
        }
        if ($ticket instanceof Ticket) {
            if (method_exists($ticket, 'customData')) {
                $context['custom_fields'] = $ticket->customData();
            }
            $context['ticket_link'] = admin_url("admin.php?page=fluent-support#/tickets/{$ticket->id}/view");
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
TicketCreatedFluentSupport::get_instance();
