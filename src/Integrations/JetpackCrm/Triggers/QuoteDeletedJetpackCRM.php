<?php

namespace Dollie\SDK\Integrations\JetpackCRM\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'quote_deleted_jetpack_crm',
    label: 'Quote Deleted',
    since: '1.0.0'
)]
/**
 * QuoteDeletedJetpackCRM.
 * php version 5.6
 *
 * @category QuoteDeletedJetpackCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * QuoteDeletedJetpackCRM
 *
 * @category QuoteDeletedJetpackCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class QuoteDeletedJetpackCRM
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
    public $trigger = 'quote_deleted_jetpack_crm';

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
            'label' => __('Quote Deleted', 'dollie'),
            'action' => 'quote_deleted_jetpack_crm',
            'common_action' => 'zbs_delete_quote',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int|string $quote_id quote ID.
     *
     * @return void
     */
    public function trigger_listener($quote_id)
    {
        if (empty($quote_id)) {
            return;
        }

        $context = [
            'quote_id' => $quote_id,
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
QuoteDeletedJetpackCRM::get_instance();
