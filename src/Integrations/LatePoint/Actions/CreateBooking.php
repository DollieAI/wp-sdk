<?php

namespace Dollie\SDK\Integrations\LatePoint\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\LatePoint\LatePoint;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'lp_create_booking',
    label: 'Create Order',
    since: '1.0.0'
)]
/**
 * CreateBooking.
 * php version 5.6
 *
 * @category CreateBooking
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateBooking
 *
 * @category CreateBooking
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateBooking extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'LatePoint';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'lp_create_booking';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Create Order', 'dollie'),
            'action' => 'lp_create_booking',
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
     * @throws Exception Exception.
     *
     * @return array
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        return LatePoint::create_or_update_booking($selected_options);
    }
}

CreateBooking::get_instance();
