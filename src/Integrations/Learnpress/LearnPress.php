<?php

namespace Dollie\SDK\Integrations\Learnpress;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

/**
 * LearnPress core integrations file
 *
 * @since 1.0.0
 */
#[Integration(
    id: 'LearnPress',
    name: 'Learnpress',
    slug: 'learnpress',
    since: '1.0.0'
)]
class LearnPress extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'LearnPress';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('LearnPress', 'dollie');
        $this->description = __('Easily Create And Sell Online Courses On Your WP Site With LearnPress.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/learnpress.png';

    }

    /**
     * Get course context data.
     *
     * @param int $course_id course.
     *
     * @return array
     */
    public static function get_lpc_course_context($course_id)
    {
        $context = [];
        $courses = get_post($course_id);
        if (empty($courses)) {
            return $context;
        }
        $context['course'] = $courses->ID;
        $context['course_name'] = $courses->post_name;
        $context['course_title'] = $courses->post_title;
        $context['course_url'] = get_permalink($course_id);

        return $context;
    }

    /**
     * Get lesson context data.
     *
     * @param int $lesson_id lesson.
     *
     * @return array
     */
    public static function get_lpc_lesson_context($lesson_id)
    {
        $context = [];
        $lesson = get_post($lesson_id);
        if (empty($lesson)) {
            return $context;
        }
        $context['lesson'] = $lesson->ID;
        $context['lesson_name'] = $lesson->post_name;
        $context['lesson_title'] = $lesson->post_title;
        $context['lesson_url'] = get_permalink($lesson_id);

        return $context;
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('LearnPress');
    }
}

IntegrationsController::register(LearnPress::class);
