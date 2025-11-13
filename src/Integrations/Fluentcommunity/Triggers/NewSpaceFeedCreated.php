<?php

namespace Dollie\SDK\Integrations\Fluentcommunity\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'fcs_new_space_feed_created',
    label: 'New Space Feed Created',
    since: '1.0.0'
)]
/**
 * NewSpaceFeedCreated.
 * php version 5.6
 *
 * @category NewSpaceFeedCreated
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * NewSpaceFeedCreated
 *
 * @category NewSpaceFeedCreated
 * @since    1.0.0
 */
class NewSpaceFeedCreated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentCommunity';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'fcs_new_space_feed_created';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register the trigger.
     *
     * @param array $triggers Existing triggers.
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('New Space Feed Created', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'fluent_community/space_feed/created',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener.
     *
     * @param object $feed The created feed object.
     * @return void
     */
    public function trigger_listener($feed)
    {
        if (empty($feed)) {
            return;
        }

        $context = [
            'feed' => $feed,

        ];

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

// Initialize the class.
NewSpaceFeedCreated::get_instance();
