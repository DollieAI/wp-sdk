<?php

namespace Dollie\SDK\Integrations\JetpackCRM\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\JetpackCRM\JetpackCRM;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'company_created_jetpack_crm',
    label: 'Company Created',
    since: '1.0.0'
)]
/**
 * CompanyCreatedJetpackCRM.
 * php version 5.6
 *
 * @category CompanyCreatedJetpackCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CompanyCreatedJetpackCRM
 *
 * @category CompanyCreatedJetpackCRM
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class CompanyCreatedJetpackCRM
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
    public $trigger = 'company_created_jetpack_crm';

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
            'label' => __('Company Created', 'dollie'),
            'action' => 'company_created_jetpack_crm',
            'common_action' => 'zbs_new_company',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int|string $company_id company ID.
     *
     * @return void
     */
    public function trigger_listener($company_id)
    {
        if (empty($company_id)) {
            return;
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => JetpackCRM::get_company_context($company_id),
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
CompanyCreatedJetpackCRM::get_instance();
