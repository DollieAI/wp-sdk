<?php

namespace Dollie\SDK\Integrations\RafflePress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\RafflePress\RafflePress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'rp_someone_registered_for_giveaway_with_specific_action',
    label: 'Someone Registered for Giveaway with Specific Action',
    since: '1.0.0'
)]
/**
 * SomeoneRegisteredForGiveawayWithSpecificAction.
 * php version 5.6
 *
 * @category SomeoneRegisteredForGiveawayWithSpecificAction
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SomeoneRegisteredForGiveawayWithSpecificAction
 *
 * @category SomeoneRegisteredForGiveawayWithSpecificAction
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class SomeoneRegisteredForGiveawayWithSpecificAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'RafflePress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'rp_someone_registered_for_giveaway_with_specific_action';

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
            'label' => __('Someone Registered for Giveaway with Specific Action', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'rafflepress_post_entry_add',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $data Giveaway Data.
     *
     * @since 1.0.0
     * @return void
     */
    public function trigger_listener($data)
    {

        $context = RafflePress::get_full_context($data);

        if (empty($context)) {
            return;
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
SomeoneRegisteredForGiveawayWithSpecificAction::get_instance();
