<?php

namespace Dollie\SDK\Integrations\Fluentcommunity\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'fc_course_updated',
    label: 'CourseUpdated',
    since: '1.0.0'
)]
/**
 * CourseUpdated.
 * php version 5.6
 *
 * @category CourseUpdated
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CourseUpdated
 *
 * @category CourseUpdated
 * @since    1.0.0
 */
class CourseUpdated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentCommunity';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'fc_course_updated';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('fluent_community/course/updated', [$this, 'trigger_listener'], 10, 2);
    }

    /**
     * Trigger listener.
     *
     * @param object $course The newly created course object.
     * @param object $update_data The updated course object.
     * @return void
     */
    public function trigger_listener($course, $update_data)
    {

        if (empty($course) || empty($update_data)) {
            return;
        }

        // Prepare context with the course object.
        $context = [
            'course' => $course,
        ];

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

// Initialize the class.
CourseUpdated::get_instance();
