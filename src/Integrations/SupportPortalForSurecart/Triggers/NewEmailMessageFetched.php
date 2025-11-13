<?php

namespace Dollie\SDK\Integrations\SupportPortalForSureCart\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'sps_new_email_message_fetched',
    label: 'New Email Message Fetched',
    since: '1.0.0'
)]
/**
 * NewEmailMessageFetched.
 * php version 5.6
 *
 * @category NewEmailMessageFetched
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * NewEmailMessageFetched
 *
 * @category NewEmailMessageFetched
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class NewEmailMessageFetched
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'SupportPortalForSureCart';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'sps_new_email_message_fetched';

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
            'label' => __('New Email Message Fetched', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'surelywp_sp_get_new_email_message',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $message Email Message.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($message)
    {

        $context = $message;
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
NewEmailMessageFetched::get_instance();
