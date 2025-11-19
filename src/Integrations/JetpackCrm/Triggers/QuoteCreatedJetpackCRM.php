<?php

namespace Dollie\SDK\Integrations\JetpackCRM\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\JetpackCRM\JetpackCRM;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'quote_created_jetpack_crm',
    label: 'Quote Created',
    since: '1.0.0'
)]
/**
 * QuoteCreatedJetpackCRM.
 * php version 5.6
 *
 * @category QuoteCreatedJetpackCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * QuoteCreatedJetpackCRM
 *
 * @category QuoteCreatedJetpackCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class QuoteCreatedJetpackCRM
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
    public $trigger = 'quote_created_jetpack_crm';

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
            'label' => __('Quote Created', 'dollie'),
            'action' => 'quote_created_jetpack_crm',
            'common_action' => 'zbs_new_quote',
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

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => JetpackCRM::get_quote_context($quote_id),
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
QuoteCreatedJetpackCRM::get_instance();
