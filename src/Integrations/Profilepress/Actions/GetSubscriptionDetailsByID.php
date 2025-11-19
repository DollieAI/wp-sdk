<?php

namespace Dollie\SDK\Integrations\Profilepress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'ppress_get_subscription_details_by_id',
    label: 'Get Subscription Details by ID',
    since: '1.0.0'
)]
/**
 * GetSubscriptionDetailsByID.
 * php version 5.6
 *
 * @category GetSubscriptionDetailsByID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.1.5
 */
/**
 * GetSubscriptionDetailsByID
 *
 * @category GetSubscriptionDetailsByID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetSubscriptionDetailsByID extends AutomateAction
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
    public $action = 'ppress_get_subscription_details_by_id';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get Subscription Details by ID', 'dollie'),
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

        if (! class_exists('\ProfilePress\Core\Membership\Models\Subscription\SubscriptionFactory')) {
            return [
                'status' => __('error', 'dollie'),
                'response' => __('ProfilePress Subscription Factory class not found. Please ensure ProfilePress is properly installed.', 'dollie'),

            ];
        }

        $subscription_id = $selected_options['subscription_id'];
        $subscription = \ProfilePress\Core\Membership\Models\Subscription\SubscriptionFactory::fromId(absint($subscription_id));

        return $subscription;
    }
}

GetSubscriptionDetailsByID::get_instance();
