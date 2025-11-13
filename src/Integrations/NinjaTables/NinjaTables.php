<?php

namespace Dollie\SDK\Integrations\NinjaTables;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'NinjaTables',
    name: 'NinjaTables',
    slug: 'ninja-tables',
    since: '1.0.0'
)]
/**
 * Ninja Tables core integrations file
 *
 * @since 1.0.0
 */
class NinjaTables extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'NinjaTables';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Ninja Tables', 'dollie');
        $this->description = __('Best Data Table Plugin for WordPress.', 'dollie');
    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('NINJA_TABLES_VERSION');
    }
}

IntegrationsController::register(NinjaTables::class);
