<?php

namespace Dollie\SDK\Integrations\Paymattic\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use WPPayForm\App\Models\Submission;

#[Action(
    id: 'wppay_payment_change_status',
    label: 'Change Payment Status ',
    since: '1.0.0'
)]
/**
 * ChangePaymentStatus.
 * php version 5.6
 *
 * @category ChangePaymentStatus
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ChangePaymentStatus
 *
 * @category ChangePaymentStatus
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ChangePaymentStatus extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Paymattic';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'wppay_payment_change_status';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Change Payment Status ', 'dollie'),
            'action' => $this->action,
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user_id.
     * @param int   $automation_id automation_id.
     * @param array $fields fields.
     * @param array $selected_options selectedOptions.
     * @psalm-suppress UndefinedMethod
     * @throws Exception Error.
     * @return array|bool|void
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        if (! (class_exists('WPPayForm\App\Models\Submission'))) {
            return;
        }
        $submisson_id = $selected_options['submission_id'];
        $new_status = $selected_options['new_status'];
        $submission_model = new Submission();
        $updated = $submission_model->updateSubmission(
            $submisson_id,
            [
                'payment_status' => $new_status,
            ]
        );
        if ($updated) {
            $submission = $submission_model->getSubmission($submisson_id);
            do_action('wppayform/after_payment_status_change', $submisson_id, $new_status); //phpcs:ignore

            return $submission->toArray();
        } else {
            return [
                'status' => 'error',
                'message' => 'Failed to update status',
            ];
        }

    }
}

ChangePaymentStatus::get_instance();
