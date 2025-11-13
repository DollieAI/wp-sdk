<?php

namespace Dollie\SDK\Integrations\Fluentcommunity\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentCommunity\App\Functions\Utility;

#[Action(
    id: 'fc_get_all_spaces',
    label: 'Get All Spaces List',
    since: '1.0.0'
)]
/**
 * GetAllSpacesList.
 * php version 5.6
 *
 * @category GetAllSpacesList
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GetAllSpacesList
 *
 * @category GetAllSpacesList
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetAllSpacesList extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentCommunity';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'fc_get_all_spaces';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Get All Spaces List', 'dollie'),
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

        // Check if FluentCommunity class exists.
        if (! class_exists('FluentCommunity\App\Functions\Utility')) {
            return [
                'status' => 'error',
                'message' => 'FluentCommunity class not found.',
            ];
        }

        // Attempt to fetch spaces and handle potential errors.
        try {
            $spaces = Utility::getSpaces();

            // Check if spaces are returned.
            if (! $spaces) {
                return [
                    'status' => 'error',
                    'message' => 'No spaces found or failed to fetch spaces.',
                ];
            }

            // Return success if spaces are fetched successfully.
            return [
                'status' => 'success',
                'message' => 'All spaces list fetched successfully',
                'spaces' => $spaces,
            ];
        } catch (Exception $e) {
            // Catch any exceptions that occur while fetching spaces.
            return [
                'status' => 'error',
                'message' => 'Error fetching spaces: ' . $e->getMessage(),
            ];
        }
    }
}

GetAllSpacesList::get_instance();
