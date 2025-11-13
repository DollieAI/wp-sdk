<?php

namespace Dollie\SDK\Integrations\Givewp\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use Give_Payment;

#[Trigger(
    id: 'givewp_donation_submit_specific_field_value',
    label: 'User Submits Donation Form Specific Field Value',
    since: '1.0.0'
)]
/**
 * GiveWPDonationSubmitSpecificFieldValue.
 * php version 5.6
 *
 * @category GiveWPDonationSubmitSpecificFieldValue
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GiveWPDonationSubmitSpecificFieldValue
 *
 * @category GiveWPDonationSubmitSpecificFieldValue
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class GiveWPDonationSubmitSpecificFieldValue
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'GiveWP';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'givewp_donation_submit_specific_field_value';

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
            'label' => __('User Submits Donation Form Specific Field Value', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'give_insert_payment',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int   $payment_id ID of payment.
     * @param array $payment_data Payment Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($payment_id, $payment_data)
    {
        if (! class_exists('Give_Payment')) {
            return;
        }
        $payment = new Give_Payment($payment_id);
        $input_array = $payment->payment_meta;
        unset($input_array['user_info']);
        $context = $input_array;
        foreach ($input_array as $key => $value) {
            $context['field_id'] = $key;
            $context['field_value'] = $value;
            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
GiveWPDonationSubmitSpecificFieldValue::get_instance();
