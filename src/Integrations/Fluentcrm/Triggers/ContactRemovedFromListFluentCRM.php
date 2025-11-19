<?php

namespace Dollie\SDK\Integrations\Fluentcrm\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use FluentCrm\App\Models\Lists;

#[Trigger(
    id: 'contact_removed_from_list_fluentcrm',
    label: 'Contact Removed from List',
    since: '1.0.0'
)]
/**
 * ContactRemovedFromListFluentCRM.
 * php version 5.6
 *
 * @category ContactRemovedFromListFluentCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ContactRemovedFromListFluentCRM
 *
 * @category ContactRemovedFromListFluentCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class ContactRemovedFromListFluentCRM
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
    public $trigger = 'contact_removed_from_list_fluentcrm';

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
            'label' => __('Contact Removed from List', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'fluentcrm_contact_removed_from_lists',
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
        if (! class_exists('FluentCrm\App\Models\Lists')) {
            return;
        }
        if (method_exists($contact, 'toArray')) {
            $contact_arr = $contact->toArray();
            $context = [];
            foreach ($list_ids as $key => $list_id) {
                $context['list_id'] = $list_id;
                $context['contact'] = $contact_arr;
                $lists = Lists::where('id', $list_id)->first();
                $context['list'] = $lists;
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
ContactRemovedFromListFluentCRM::get_instance();
