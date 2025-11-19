<?php

namespace Dollie\SDK\Integrations\JetpackCRM\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'transaction_deleted_jetpack_crm',
    label: 'Transaction Deleted',
    since: '1.0.0'
)]
/**
 * TransactionDeletedJetpackCRM.
 * php version 5.6
 *
 * @category TransactionDeletedJetpackCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * TransactionDeletedJetpackCRM
 *
 * @category TransactionDeletedJetpackCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class TransactionDeletedJetpackCRM
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'JetpackCRM';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'transaction_deleted_jetpack_crm';

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
            'label' => __('Transaction Deleted', 'dollie'),
            'action' => 'transaction_deleted_jetpack_crm',
            'common_action' => 'zbs_delete_transaction',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int|string $transaction_id transaction ID.
     *
     * @return void
     */
    public function trigger_listener($transaction_id)
    {
        if (empty($transaction_id)) {
            return;
        }

        $context = [
            'transaction_id' => $transaction_id,
        ];

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
TransactionDeletedJetpackCRM::get_instance();
