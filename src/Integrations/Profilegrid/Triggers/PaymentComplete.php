<?php

namespace Dollie\SDK\Integrations\Profilegrid\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'pg_payment_complete',
    label: 'Payment Complete',
    since: '1.0.0'
)]
/**
 * PaymentComplete.
 * php version 5.6
 *
 * @category PaymentComplete
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PaymentComplete
 *
 * @category PaymentComplete
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class PaymentComplete
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ProfileGrid';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'pg_payment_complete';

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
            'label' => __('Payment Complete', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'profilegrid_payment_complete',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int $gid Group ID.
     * @param int $user_id User ID.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($gid, $user_id)
    {
        $context = WordPress::get_user_context($user_id);
        $context['group_id'] = $gid;
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
PaymentComplete::get_instance();
