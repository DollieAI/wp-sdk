<?php

namespace Dollie\SDK\Integrations\Tutorlms\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use Tutor\Helpers\QueryHelper;

#[Action(
    id: 'tlms_list_enrolled_users',
    label: 'List Enrolled Users',
    since: '1.0.0'
)]
/**
 * ListEnrolledUsers.
 * php version 5.6
 *
 * @category ListEnrolledUsers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ListEnrolledUsers
 *
 * @category ListEnrolledUsers
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ListEnrolledUsers extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'TutorLMS';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'tlms_list_enrolled_users';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('List Enrolled Users', 'dollie'),
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
     * @return array
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $context = [];
        global $wpdb;
        $course_id = isset($selected_options['course_id']) ? $selected_options['course_id'] : '0';

        if (! function_exists('tutor_utils') || ! class_exists('Tutor\Helpers\QueryHelper') || ! function_exists('tutor')) {
            return [
                'status' => 'error',
                'message' => __('Required Tutor LMS functions or classes are not available.', 'dollie'),

            ];
        }

        $course = get_post((int) $course_id);
        if (! $course) {
            return [
                'status' => 'error',
                'message' => __('Invalid course ID provided.', 'dollie'),

            ];
        }

        $clean_users = [];
        $enrolled_users = tutor_utils()->get_students_data_by_course_id($course_id, 'ID', true);

        if (empty($enrolled_users)) {
            return [
                'status' => 'error',
                'message' => __('No enrolled users found for this course.', 'dollie'),

            ];
        }

        foreach ($enrolled_users as $user_obj) {
            $user_array = (array) $user_obj;
            unset($user_array['user_pass'], $user_array['user_activation_key']);
            $enrollments = QueryHelper::get_all(
                $wpdb->posts,
                [
                    'post_author' => $user_array['ID'],
                    'post_type' => [
                        tutor()->enrollment_post_type,
                        'course-bundle',
                    ],
                ],
                'ID'
            );
            if (! empty($enrollments) && is_array($enrollments)) {
                $enrollment = $enrollments[0];
                $user_array = array_merge(
                    $user_array,
                    [
                        'enrollment_id' => isset($enrollment->ID) ? $enrollment->ID : '',
                        'enrollment_status' => isset($enrollment->post_status) ? $enrollment->post_status : '',
                        'enrollment_title' => isset($enrollment->post_title) ? $enrollment->post_title : '',
                        'enrollment_date' => isset($enrollment->post_date) ? $enrollment->post_date : '',
                    ]
                );
            }
            $clean_users[] = $user_array;
        }

        return [
            'status' => 'success',
            'course_id' => $course_id,
            'course_name' => get_the_title($course_id),
            'enrolled_users' => $clean_users,
        ];
    }
}

ListEnrolledUsers::get_instance();
