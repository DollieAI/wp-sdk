<?php

namespace Dollie\SDK\Integrations\AdvancedCoupons\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_receives_store_credit',
    label: 'User Store Credit Exceeds Specific Amount',
    since: '1.0.0'
)]
/**
 * UserReceivesStoreCredit.
 * php version 5.6
 *
 * @category UserReceivesStoreCredit
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserReceivesStoreCredit
 *
 * @category UserReceivesStoreCredit
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserReceivesStoreCredit
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'AdvancedCoupons';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_receives_store_credit';

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
            'label' => __('User Store Credit Exceeds Specific Amount', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'acfw_create_store_credit_entry',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $data Data.
     * @return void
     */
    public function trigger_listener($data)
    {

        if (! isset($data['type']) || 'decrease' === $data['type']) {
            return;
        }

        $user_id = (isset($data['user_id'])) ? intval($data['user_id']) : 0;

        if (0 === $user_id) {
            return;
        }

        $balance = floatval($data['amount']);

        $trigger_data['credit_amount'] = $balance;

        $context = array_merge($trigger_data, WordPress::get_user_context($user_id));

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
UserReceivesStoreCredit::get_instance();
