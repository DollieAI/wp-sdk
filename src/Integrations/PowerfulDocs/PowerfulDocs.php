<?php

namespace Dollie\SDK\Integrations\PowerfulDocs;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'PowerfulDocs',
    name: 'PowerfulDocs',
    slug: 'powerful-docs',
    since: '1.0.0'
)]
/**
 * PowerfulDocs core integrations file
 *
 * @since 1.0.0
 */
class PowerfulDocs extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'PowerfulDocs';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Powerful Docs', 'dollie');
        $this->description = __('Easily build documentation website with AJAX based live search functionality and keep track of search term. This plugin provides shortcodes to display category list & live search input box.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/powerfuldocs.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('\PowerfulDocs\Loader');
    }
}

IntegrationsController::register(PowerfulDocs::class);
