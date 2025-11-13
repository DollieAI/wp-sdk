<?php

namespace Dollie\SDK\Integrations\Suremail\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'suremail_mail_blocked',
    label: 'Mail Blocked To Send',
    since: '1.0.0'
)]
/**
 * SureMailMailBlocked.
 * php version 5.6
 *
 * @category SureMailMailBlocked
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SureMailMailBlocked
 *
 * @category SureMailMailBlocked
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class SureMailMailBlocked
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'SureMail';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'suremail_mail_blocked';

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
            'label' => __('Mail Blocked To Send', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'suremails_mail_blocked',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     *  Trigger listener
     *
     * @param array $mail_data trigger data.
     *
     * @return void
     */
    public function trigger_listener($mail_data)
    {
        $context = $mail_data;

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
SureMailMailBlocked::get_instance();
