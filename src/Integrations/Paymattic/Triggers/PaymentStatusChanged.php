<?php

namespace Dollie\SDK\Integrations\Paymattic\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use WPPayForm\App\Models\Submission;

#[Trigger(
    id: 'wppay_payment_status_changed',
    label: 'Payment Status Changed',
    since: '1.0.0'
)]
/**
 * Paymattic.
 * php version 5.6
 *
 * @category PaymentStatusChanged
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PaymentStatusChanged
 *
 * @category PaymentStatusChanged
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class PaymentStatusChanged
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Paymattic';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wppay_payment_status_changed';

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
            'label' => __('Payment Status Changed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wppayform/after_payment_status_change',
            'function' => [$this, 'trigger_listener'],
            'priority' => 20,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int $submission_id submission id.
     * @param string  $new_status new status.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($submission_id, $new_status)
    {
        if (! isset($submission_id)) {
            return;
        }
        if (! (class_exists('WPPayForm\App\Models\Submission'))) {
            return;
        }
        $submission_model = new Submission();
        $submission = $submission_model->getSubmission($submission_id);
        $context = $submission->toArray();
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
PaymentStatusChanged::get_instance();
