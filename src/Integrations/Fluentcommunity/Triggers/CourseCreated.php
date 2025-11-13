<?php

namespace Dollie\SDK\Integrations\Fluentcommunity\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'fc_course_created',
    label: 'CourseCreated',
    since: '1.0.0'
)]
/**
 * CourseCreated.
 * php version 5.6
 *
 * @category CourseCreated
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CourseCreated
 *
 * @category CourseCreated
 * @since    1.0.0
 */
class CourseCreated
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
    public $trigger = 'fc_course_created';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('fluent_community/course/created', [$this, 'trigger_listener'], 10, 1);
    }

    /**
     * Trigger listener.
     *
     * @param object $course The newly created course object.
     * @return void
     */
    public function trigger_listener($course)
    {

        if (empty($course)) {
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
CourseCreated::get_instance();
