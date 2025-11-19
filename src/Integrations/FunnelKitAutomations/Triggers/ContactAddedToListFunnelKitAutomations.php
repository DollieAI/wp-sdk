<?php

namespace Dollie\SDK\Integrations\FunnelKitAutomations\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\FunnelKitAutomations\FunnelKitAutomations;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'contact_added_to_list_funnel_kit_automations',
    label: 'Contact Added to List',
    since: '1.0.0'
)]
/**
 * ContactAddedToListFunnelKitAutomations.
 * php version 5.6
 *
 * @category ContactAddedToListFunnelKitAutomations
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ContactAddedToListFunnelKitAutomations
 *
 * @category ContactAddedToListFunnelKitAutomations
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class ContactAddedToListFunnelKitAutomations
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FunnelKitAutomations';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'contact_added_to_list_funnel_kit_automations';

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
            'action' => 'contact_added_to_list_funnel_kit_automations',
            'common_action' => 'bwfan_contact_added_to_lists',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param  mixed  $lists Added Lists.
     * @param  object $bwfcm_contact Contact.
     *
     * @return void
     */
    public function trigger_listener($lists, $bwfcm_contact)
    {

        if (! isset($bwfcm_contact->contact)) {
            return;
        }

        $contact_data = FunnelKitAutomations::get_contact_context($bwfcm_contact->contact);

        if (! is_array($lists) && is_object($lists) && method_exists($lists, 'get_id')) {

            $list_data = FunnelKitAutomations::get_list_context($lists->get_id());

            if (empty($list_data)) {
                return;
            }

            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => array_merge($list_data, $contact_data),
                ]
            );
        }

        // @phpstan-ignore-next-line
        foreach ($lists as $list) {
            if (! is_object($list) || ! method_exists($list, 'get_id')) {
                continue;
            }

            $list_data = FunnelKitAutomations::get_list_context($list->get_id());

            if (empty($list_data)) {
                continue;
            }

            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => array_merge($list_data, $contact_data),
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
ContactAddedToListFunnelKitAutomations::get_instance();
