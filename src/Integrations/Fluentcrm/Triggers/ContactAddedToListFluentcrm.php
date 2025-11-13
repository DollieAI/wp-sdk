<?php

namespace Dollie\SDK\Integrations\Fluentcrm\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'contact_added_to_list_fluentcrm',
    label: 'Contact Added to List',
    since: '1.0.0'
)]
/**
 * ContactAddedToListFluentCRM.
 * php version 5.6
 *
 * @category ContactAddedToListFluentCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ContactAddedToListFluentCRM
 *
 * @category ContactAddedToListFluentCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class ContactAddedToListFluentCRM
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
    public $trigger = 'contact_added_to_list_fluentcrm';

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
            'label' => __('Contact Added to List', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'fluentcrm_contact_added_to_lists',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array  $list_ids List IDs.
     * @param object $contact Contact.
     * @return void
     */
    public function trigger_listener($list_ids, $contact)
    {
        if (empty($list_ids)) {
            return;
        }
        if (method_exists($contact, 'toArray')) {
            $contact_arr = $contact->toArray();
            $context = [];
            foreach ($list_ids as $key => $list_id) {
                $context['list_id'] = $list_id;
                $context['contact'] = $contact_arr;
                $lists = $contact_arr['lists'];
                if (is_array($lists)) {
                    $list_key = array_search($list_id, array_column($lists, 'id'));
                    $context['list'] = $lists[$list_key];
                    unset($context['contact']['lists']);
                }
                AutomationController::dollie_trigger_handle_trigger(
                    [
                        'trigger' => $this->trigger,
                        'context' => $context,
                    ]
                );
            }
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
ContactAddedToListFluentCRM::get_instance();
