<?php

namespace Dollie\SDK\Integrations\Suremail\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'suremail_mail_failed',
    label: 'Mail Failed To Send',
    since: '1.0.0'
)]
/**
 * SureMailMailFailed.
 * php version 5.6
 *
 * @category SureMailMailFailed
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SureMailMailFailed
 *
 * @category SureMailMailFailed
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class SureMailMailFailed
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
    public $trigger = 'suremail_mail_failed';

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
            'label' => __('Mail Failed To Send', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wp_mail_failed',
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
SureMailMailFailed::get_instance();
