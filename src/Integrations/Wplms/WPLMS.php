<?php

namespace Dollie\SDK\Integrations\Wplms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WPLMS',
    name: 'Wplms',
    slug: 'wplms',
    since: '1.0.0'
)]
/**
 * WPLMS core integrations file
 *
 * @since 1.0.0
 */
class WPLMS extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPLMS';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WPLMS', 'dollie');
        $this->description = __('WPLMS is a social network plugin for WordPress that allows you to quickly add a social network.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/WPLMS.svg';

    }

    /**
     * Get customer context data.
     *
     * @param int $course_id course.
     *
     * @return array
     */
    public static function get_wplms_course_context($course_id)
    {
        $courses = get_post($course_id);
        if (is_null($courses)) {
            return [];
        }
        $context['wplms_course'] = $courses->ID;
        $context['wplms_course_name'] = $courses->post_name;
        $context['wplms_course_title'] = $courses->post_title;

        return $context;
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WPLMS_Init');
    }
}

IntegrationsController::register(WPLMS::class);
