<?php

namespace Dollie\SDK\Integrations\FluentSupport\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use FluentSupport\App\Models\Ticket;

#[Trigger(
    id: 'ticket_created_product_fluent_support',
    label: 'Ticket Created for Product',
    since: '1.0.0'
)]
/**
 * TicketCreatedProductFluentSupport.
 * php version 5.6
 *
 * @category TicketCreatedProductFluentSupport
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * TicketCreatedProductFluentSupport
 *
 * @category TicketCreatedProductFluentSupport
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class TicketCreatedProductFluentSupport
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
    public $trigger = 'ticket_created_product_fluent_support';

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
            'label' => __('Ticket Created for Product', 'dollie'),
            'action' => 'ticket_created_product_fluent_support',
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

        if (! class_exists('\FluentSupport\App\Models\Ticket')) {
            return;
        }

        if ($ticket instanceof Ticket) {
            $context['ticket_product_id'] = $ticket->product_id;
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
TicketCreatedProductFluentSupport::get_instance();
