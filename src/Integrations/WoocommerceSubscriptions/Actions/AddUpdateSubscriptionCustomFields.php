<?php

namespace Dollie\SDK\Integrations\WoocommerceSubscriptions\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'wc_add_update_subscription_custom_fields',
    label: 'Add or Update Custom Fields',
    since: '1.0.0'
)]
/**
 * AddUpdateSubscriptionCustomFields.
 * php version 5.6
 *
 * @category AddUpdateSubscriptionCustomFields
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddUpdateSubscriptionCustomFields
 *
 * @category AddUpdateSubscriptionCustomFields
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddUpdateSubscriptionCustomFields extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WoocommerceSubscriptions';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'wc_add_update_subscription_custom_fields';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Add or Update Custom Fields', 'dollie'),
            'action' => 'wc_add_update_subscription_custom_fields',
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
     * @return object|array|null|void
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $subscription_id = $selected_options['subscription_id'];
        $meta = $selected_options['subscription_meta'];

        // Check if function exists to get subscription object.
        if (! function_exists('wcs_get_subscription')) {
            return [
                'status' => 'error',
                'message' => __('wcs_get_subscription function not found.', 'dollie'),

            ];
        }

        // Get subscription object using subscription id.
        $subscription = wcs_get_subscription($subscription_id);

        // Update meta data for subscription.
        if ($subscription) {
            foreach ($meta as $fields) {
                $meta_key = $fields['meta_key'];
                $meta_value = $fields['meta_value'];
                $subscription->update_meta_data($meta_key, $meta_value);
            }
            // Save subscription.
            $subscription->save();

            // Return subscription data.
            return $subscription->get_data();
        } else {
            return [
                'status' => 'error',
                'message' => 'Subscription not found for the provided Subscription ID.',
            ];
        }
    }
}

AddUpdateSubscriptionCustomFields::get_instance();
