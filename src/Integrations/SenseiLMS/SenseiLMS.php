<?php

namespace Dollie\SDK\Integrations\SenseiLMS;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'SenseiLMS',
    name: 'SenseiLms',
    slug: 'sensei-lms',
    since: '1.0.0'
)]
/**
 * SenseiLMS core integrations file
 *
 * @since 1.0.0
 */
class SenseiLMS extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'SenseiLMS';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Sensei LMS', 'dollie');
        $this->description = __('Learning Management System', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/tutorlms.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('Sensei_Main');
    }
}

IntegrationsController::register(SenseiLMS::class);
