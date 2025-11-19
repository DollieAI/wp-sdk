<?php

namespace Dollie\SDK\Integrations\MailMint\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'mail_mint_send_campaign_email',
    label: 'Campaign Email Sent',
    since: '1.0.0'
)]
/**
 * CampaignSend.
 * php version 5.6
 *
 * @category CampaignSend
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CampaignSend
 *
 * @category CampaignSend
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class CampaignSend
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'MailMint';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'mail_mint_send_campaign_email';

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
     * Register action.
     *
     * @param array $triggers trigger data.
     *
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Campaign Email Sent', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'mailmint_after_campaign_start',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $data Appointment Data.
     *
     * @return void
     */
    public function trigger_listener($data)
    {
        if (empty($data)) {
            return;
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $data,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
CampaignSend::get_instance();
