<?php

namespace Dollie\SDK\Integrations\FluentBoards;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'FluentBoards',
    name: 'FluentBoards',
    slug: 'fluent-boards',
    since: '1.0.0'
)]
/**
 * FluentBoards core integrations file
 *
 * @since 1.0.0
 */
class FluentBoards extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'FluentBoards';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('FluentBoards', 'dollie');
        $this->description = __('FluentBoards is the Ultimate Scheduling Solution for WordPress. Harness the power of unlimited appointments, bookings, webinars, events, sales calls, etc., and save time with scheduling automation.', 'dollie');
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('FLUENT_BOARDS');
    }
}

IntegrationsController::register(FluentBoards::class);
