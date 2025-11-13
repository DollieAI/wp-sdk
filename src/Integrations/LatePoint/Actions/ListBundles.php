<?php

namespace Dollie\SDK\Integrations\LatePoint\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'lp_get_all_bundles',
    label: 'List Bundles',
    since: '1.0.0'
)]
/**
 * ListBundles.
 * php version 5.6
 *
 * @category ListBundles
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ListBundles
 *
 * @category ListBundles
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ListBundles extends AutomateAction
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
    public $action = 'lp_get_all_bundles';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('List Bundles', 'dollie'),
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
     * @param array $selected_options selected_options.
     *
     * @return array|void
     *
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        global $wpdb;

        $bundles = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}latepoint_bundles", ARRAY_A);

        if (empty($bundles)) {
            return ['message' => 'No bundles found in the database.'];
        }

        return $bundles;
    }
}

ListBundles::get_instance();
