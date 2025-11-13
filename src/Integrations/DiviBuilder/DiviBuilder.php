<?php

namespace Dollie\SDK\Integrations\DiviBuilder;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use ET_Builder_Plugin;

#[Integration(
    id: 'DiviBuilder',
    name: 'DiviBuilder',
    slug: 'divi-builder',
    since: '1.0.0'
)]
/**
 * DiviBuilder core integrations file
 *
 * @since 1.0.0
 */
class DiviBuilder extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'DiviBuilder';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Divi', 'dollie');
        $this->description = __('The Ultimate WordPress Page Builder.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/divi.svg';

        parent::__construct();
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('ET_BUILDER_THEME') || class_exists(ET_Builder_Plugin::class);
    }
}

IntegrationsController::register(DiviBuilder::class);
