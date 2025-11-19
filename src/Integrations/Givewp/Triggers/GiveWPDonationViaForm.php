<?php

namespace Dollie\SDK\Integrations\Givewp\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use Give_Payment;

#[Trigger(
    id: 'givewp_donation_via_form',
    label: 'User Submits Donation Form',
    since: '1.0.0'
)]
/**
 * GiveWPDonationViaForm.
 * php version 5.6
 *
 * @category GiveWPDonationViaForm
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GiveWPDonationViaForm
 *
 * @category GiveWPDonationViaForm
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class GiveWPDonationViaForm
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
    public $trigger = 'givewp_donation_via_form';

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
            'label' => __('User Submits Donation Form', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'give_update_payment_status',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int   $payment_id ID of payment.
     * @param array $status Current donation status.
     * @param array $old_status Old donation status.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($payment_id, $status, $old_status)
    {
        if (! class_exists('Give_Payment')) {
            return;
        }

        if (! function_exists('give_get_donor_donation_comment')) {
            return;
        }

        $payment = new Give_Payment($payment_id);
        if ('publish' !== $status) {
            return;
        }

        $address_data = $payment->address;
        $context['first_name'] = $payment->first_name;
        $context['last_name'] = $payment->last_name;
        $context['email'] = $payment->email;
        $context['currency'] = $payment->currency;
        $context['donated_amount'] = $payment->subtotal;
        $context['donation_amount'] = $payment->subtotal;
        $context['form_id'] = (int) $payment->form_id;
        $context['form_title'] = $payment->form_title;
        $context['name_title_prefix'] = $payment->title_prefix;
        $context['date'] = $payment->date;
        if (is_array($address_data)) {
            $context['address_line_1'] = $address_data['line1'];
            $context['address_line_2'] = $address_data['line2'];
            $context['city'] = $address_data['city'];
            $context['state'] = $address_data['state'];
            $context['zip'] = $address_data['zip'];
            $context['country'] = $address_data['country'];
        }
        // Payment meta.
        $payment_meta = $payment->get_meta();
        if (is_array($payment_meta) && isset($payment_meta['user_info'])) {
            unset($payment_meta['user_info']);
        }
        $context['payment_meta'] = $payment_meta;
        $donor_comment = give_get_donor_donation_comment($payment_id, $payment->donor_id);
        $context['comment'] = (is_array($donor_comment) && isset($donor_comment['comment_content'])) ? $donor_comment : '';
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
GiveWPDonationViaForm::get_instance();
