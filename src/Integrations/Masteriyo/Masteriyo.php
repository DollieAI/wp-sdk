<?php

namespace Dollie\SDK\Integrations\Masteriyo;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'Masteriyo',
    name: 'Masteriyo',
    slug: 'masteriyo',
    since: '1.0.0'
)]
/**
 * Masteriyo core integrations file
 *
 * @since   1.0.0
 */
class Masteriyo extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'Masteriyo';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Masteriyo', 'dollie');
        $this->description = __('A WordPress LMS Plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/masteriyo.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return function_exists('masteriyo');
    }
}

IntegrationsController::register(Masteriyo::class);
