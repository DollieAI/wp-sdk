<?php

namespace Dollie\SDK\Integrations\Clickwhale;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'ClickWhale',
    name: 'Clickwhale',
    slug: 'clickwhale',
    since: '1.0.0'
)]
/**
 * ClickWhale core integrations file
 *
 * @since 1.0.0
 */
/**
 * Class ClickWhale
 */
class ClickWhale extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'ClickWhale';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('ClickWhale', 'dollie');
        $this->description = __('ClickWhale is a powerful link management and tracking plugin for WordPress that helps you create, manage, and track short links with detailed analytics.', 'dollie');
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('ClickWhale\ClickWhale') || defined('CLICKWHALE_VERSION');
    }
}

IntegrationsController::register(ClickWhale::class);
