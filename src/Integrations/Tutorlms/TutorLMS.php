<?php

namespace Dollie\SDK\Integrations\Tutorlms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'TutorLMS',
    name: 'Tutorlms',
    slug: 'tutorlms',
    since: '1.0.0'
)]
/**
 * TutorLMS core integrations file
 *
 * @since 1.0.0
 */
class TutorLMS extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'TutorLMS';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('TutorLMS', 'dollie');
        $this->description = __('Easily Create And Sell Online Courses On Your WP Site With TutorLMS.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/tutorlms.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('\TUTOR\Tutor');
    }
}

IntegrationsController::register(TutorLMS::class);
