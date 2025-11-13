<?php

namespace Dollie\SDK\Integrations\Fluentsmtp\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'fs_email_delivery_error',
    label: 'Error in Email Delivery',
    since: '1.0.0'
)]
/**
 * ErrorInEmailDelivery.
 * php version 5.6
 *
 * @category ErrorInEmailDelivery
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ErrorInEmailDelivery
 *
 * @category ErrorInEmailDelivery
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class ErrorInEmailDelivery
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentSMTP';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'fs_email_delivery_error';

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
            'label' => __('Error in Email Delivery', 'dollie'),
            'action' => 'fs_email_delivery_error',
            'common_action' => 'fluentmail_email_sending_failed_no_fallback',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $log_id ID.
     * @param array $handler ID.
     * @param array $data Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($log_id, $handler, $data)
    {
        if (empty($data)) {
            return;
        }

        $context['to'] = unserialize($data['to']);
        $context['from'] = $data['from'];
        $context['subject'] = $data['subject'];
        $context['body'] = $data['body'];
        $context['attachments'] = unserialize($data['attachments']);
        $context['status'] = $data['status'];
        $context['response'] = unserialize($data['response']);
        $context['headers'] = unserialize($data['headers']);
        $context['extra'] = unserialize($data['extra']);

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
ErrorInEmailDelivery::get_instance();
