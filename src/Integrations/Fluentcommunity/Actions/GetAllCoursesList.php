<?php

namespace Dollie\SDK\Integrations\Fluentcommunity\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use FluentCommunity\App\Functions\Utility;

#[Action(
    id: 'fc_get_all_courses',
    label: 'Get All Courses List',
    since: '1.0.0'
)]
/**
 * GetAllCoursesList.
 * php version 5.6
 *
 * @category GetAllCoursesList
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GetAllCoursesList
 *
 * @category GetAllCoursesList
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetAllCoursesList extends AutomateAction
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
    public $action = 'fc_get_all_courses';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Get All Courses List', 'dollie'),
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

        // Attempt to fetch courses and handle potential errors.
        try {
            $courses = Utility::getCourses();
            if (! $courses) {
                return [
                    'status' => 'error',
                    'message' => 'No courses found or failed to fetch courses.',
                ];
            }

            return [
                'status' => 'success',
                'message' => 'All courses list fetched successfully',
                'courses' => $courses,
            ];
        } catch (Exception $e) {
            // Catch any exceptions that occur while fetching courses.
            return [
                'status' => 'error',
                'message' => 'Error fetching courses: ' . $e->getMessage(),
            ];
        }
    }
}

GetAllCoursesList::get_instance();
