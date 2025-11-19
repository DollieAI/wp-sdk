<?php

namespace Dollie\SDK\Integrations\Fluentcrm\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use FluentCrm\App\Models\Subscriber;

#[Trigger(
    id: 'new_contact_added_fluentcrm',
    label: 'New Contact Added',
    since: '1.0.0'
)]
/**
 * NewContactAddedFluentCRM.
 * php version 5.6
 *
 * @category NewContactAddedFluentCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * NewContactAddedFluentCRM
 *
 * @category NewContactAddedFluentCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class NewContactAddedFluentCRM
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentCRM';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'new_contact_added_fluentcrm';

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
            'label' => __('New Contact Added', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'fluentcrm_contact_created',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $contact Contact.
     * @return void
     */
    public function trigger_listener($contact)
    {
        if (empty($contact)) {
            return;
        }

        /**
         * Ignore line
         *
         * @phpstan-ignore-next-line
         */
        $subscriber = Subscriber::with(['tags', 'lists'])->find($contact->id);
        $customer_fields = $subscriber->custom_fields();

        $context['contact']['details'] = $subscriber;
        $context['contact']['custom'] = $customer_fields;

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
NewContactAddedFluentCRM::get_instance();
