<?php

namespace Dollie\SDK\Integrations\UltimateMember;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

/**
 * UltimateMember core integrations file
 *
 * @since   1.0.0
 */
#[Integration(
    id: 'UltimateMember',
    name: 'UltimateMember',
    slug: 'ultimate-member',
    since: '1.0.0'
)]
class UltimateMember extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'UltimateMember';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('UltimateMember', 'dollie');
        $this->description = __('A user profile plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/ultimatemember.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('UM') || defined('um_url');
    }
}

IntegrationsController::register(UltimateMember::class);
