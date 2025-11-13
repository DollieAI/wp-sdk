<?php

namespace Dollie\SDK\Integrations\Profilepress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'ppress_get_plan_details_by_id',
    label: 'Get Plan Details by ID',
    since: '1.0.0'
)]
/**
 * GetPlanDetailsByID.
 * php version 5.6
 *
 * @category GetPlanDetailsByID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.1.5
 */
/**
 * GetPlanDetailsByID
 *
 * @category GetPlanDetailsByID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetPlanDetailsByID extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ProfilePress';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'ppress_get_plan_details_by_id';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get Plan Details by ID', 'dollie'),
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
     * @return object|array|void
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! class_exists('\ProfilePress\Core\Membership\Models\Plan\PlanFactory')) {
            return [
                'status' => __('error', 'dollie'),
                'response' => __('ProfilePress Plan Factory class not found. Please ensure ProfilePress is properly installed.', 'dollie'),

            ];
        }

        $plan_id = $selected_options['plan_id'];
        $plan = \ProfilePress\Core\Membership\Models\Plan\PlanFactory::fromId(absint($plan_id));

        return $plan;
    }
}

GetPlanDetailsByID::get_instance();
