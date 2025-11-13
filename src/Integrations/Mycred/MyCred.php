<?php

namespace Dollie\SDK\Integrations\Mycred;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'MyCred',
    name: 'Mycred',
    slug: 'mycred',
    since: '1.0.0'
)]
/**
 * MyCred core integrations file
 *
 * @since 1.0.0
 */
class MyCred extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'MyCred';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('MyCred', 'dollie');
        $this->description = __('A free points management plugin for WordPress.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/mycred.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('myCRED_Core');
    }
}

IntegrationsController::register(MyCred::class);
