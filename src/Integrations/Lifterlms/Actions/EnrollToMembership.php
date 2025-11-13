<?php

namespace Dollie\SDK\Integrations\Lifterlms\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\LifterLMS\LifterLMS;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'lms_enroll_to_membership',
    label: 'Enroll User in a Membership',
    since: '1.0.0'
)]
/**
 * EnrollToMembership.
 * php version 5.6
 *
 * @category EnrollToMembership
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * EnrollToMembership
 *
 * @category EnrollToMembership
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class EnrollToMembership extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'LifterLMS';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'lms_enroll_to_membership';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Enroll User in a Membership', 'dollie'),
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
     *
     * @psalm-suppress InvalidScalarArgument
     * @psalm-suppress UndefinedMethod
     *
     * @return bool|array|object
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        if (! function_exists('llms_enroll_student')) {
            return [
                'status' => 'error',
                'message' => __('LifterLMS enrollment function not found.', 'dollie'),

            ];
        }
        $membership_id = isset($selected_options['llms_membership']) ? $selected_options['llms_membership'] : '0';
        $membership = get_post((int) $membership_id);

        if (! $membership) {
            return [
                'status' => 'error',
                'message' => __('No membership is available ', 'dollie'),

            ];
        }
        llms_enroll_student($user_id, $membership_id, 'SureTriggers');

        $membership_data = LifterLMS::get_lms_membership_context($membership_id);

        return $membership_data;
    }
}

EnrollToMembership::get_instance();
