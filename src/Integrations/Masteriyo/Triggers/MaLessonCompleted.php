<?php

namespace Dollie\SDK\Integrations\Masteriyo\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ma_lms_lesson_completed',
    label: 'Lesson Completed',
    since: '1.0.0'
)]
/**
 * MaLessonCompleted.
 * php version 5.6
 *
 * @category MaLessonCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * MaLessonCompleted
 *
 * @category MaLessonCompleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class MaLessonCompleted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Masteriyo';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ma_lms_lesson_completed';

    /**
     * Constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register action.
     *
     * @param array $triggers trigger data.
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Lesson Completed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'masteriyo_new_course_progress_item',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $item_id The new course progress item ID.
     * @param object $object The new course progress item object.
     * @return void
     */
    public function trigger_listener($item_id, $object)
    {

        if (! method_exists($object, 'get_item_type')) {
            return;
        }
        if ('lesson' !== $object->get_item_type()) {
            return;
        }
        if (! function_exists('masteriyo_get_lesson')) {
            return;
        }
        if (method_exists($object, 'get_item_id') && method_exists($object, 'get_user_id')) {
            $lesson = masteriyo_get_lesson($object->get_item_id());
            $context = array_merge(
                WordPress::get_user_context($object->get_user_id()),
                $lesson->get_data()
            );

            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
MaLessonCompleted::get_instance();
