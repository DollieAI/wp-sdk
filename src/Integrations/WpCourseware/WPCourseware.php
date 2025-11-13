<?php

namespace Dollie\SDK\Integrations\WPCourseware;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WPCourseware',
    name: 'WpCourseware',
    slug: 'wp-courseware',
    since: '1.0.0'
)]
/**
 * WPCourseware core integrations file
 *
 * @since 1.0.0
 */
class WPCourseware extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPCourseware';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WP Courseware', 'dollie');
        $this->description = __('WP Courseware is a popular WordPress plugin for creating and managing online courses. It allows you to easily organize course content, track student progress, and create engaging learning experiences on your WordPress website.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wp-courseware.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WPCW_Requirements');
    }
}

IntegrationsController::register(WPCourseware::class);
