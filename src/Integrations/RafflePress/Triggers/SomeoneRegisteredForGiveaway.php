<?php

namespace Dollie\SDK\Integrations\RafflePress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\RafflePress\RafflePress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'rp_someone_registered_for_giveaway',
    label: 'Someone Registered for Giveaway',
    since: '1.0.0'
)]
/**
 * SomeoneRegisteredForGiveaway.
 * php version 5.6
 *
 * @category SomeoneRegisteredForGiveaway
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SomeoneRegisteredForGiveaway
 *
 * @category SomeoneRegisteredForGiveaway
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class SomeoneRegisteredForGiveaway
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
    public $trigger = 'rp_someone_registered_for_giveaway';

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
            'label' => __('Someone Registered for Giveaway', 'dollie'),
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
SomeoneRegisteredForGiveaway::get_instance();
