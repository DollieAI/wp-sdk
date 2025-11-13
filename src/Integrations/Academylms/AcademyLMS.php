<?php

namespace Dollie\SDK\Integrations\Academylms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

/**
 * AcademyLMS core integrations file
 *
 * @since 1.0.0
 */
#[Integration(
    id: 'AcademyLMS',
    name: 'Academylms',
    slug: 'academylms',
    since: '1.0.0'
)]
class AcademyLMS extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'AcademyLMS';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Academy LMS', 'dollie');
        $this->description = __('A Powerful WordPress Ad Management Plugin. Advanced Ads is a great plugin that makes it easier to manage your ads.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/academy.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('Academy');
    }
}

IntegrationsController::register(AcademyLMS::class);
