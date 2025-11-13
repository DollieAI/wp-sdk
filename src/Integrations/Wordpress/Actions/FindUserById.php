<?php

namespace Dollie\SDK\Integrations\Wordpress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'find_user_by_id',
    label: 'Find User By ID',
    since: '1.0.0'
)]
/**
 * FindUserByID.
 * php version 5.6
 *
 * @category FindUserByID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * FindUserByID
 *
 * @category FindUserByID
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class FindUserByID extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WordPress';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'find_user_by_id';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Find User By ID', 'dollie'),
            'action' => 'find_user_by_id',
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
     * @return array|bool
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $response = [];
        $wp_user_id = $selected_options['wp_user_id'];
        $user_exist = get_userdata($wp_user_id);
        if (! $user_exist) {
            $response['user_exist'] = 'no';
        } else {
            $user = WordPress::get_user_context($wp_user_id);
            $all_meta = (array) get_user_meta($wp_user_id);

            foreach ($all_meta as $key => $meta) {
                $meta = (array) $meta;
                $response['meta_' . $key] = $meta[0];
            }
            $response['user_exist'] = 'yes';
            $response = array_merge($user, $response);
        }

        return $response;

    }
}

FindUserByID::get_instance();
