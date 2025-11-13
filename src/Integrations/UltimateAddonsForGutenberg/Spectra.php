<?php

namespace Dollie\SDK\Integrations\UltimateAddonsForGutenberg;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'Spectra',
    name: 'UltimateAddonsForGutenberg',
    slug: 'ultimate-addons-for-gutenberg',
    since: '1.0.0'
)]
class Spectra extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'Spectra';

    public function __construct()
    {
        $this->name = __('Spectra', 'dollie');
        $this->description = __('Supercharge the Gutenberg editor with beautiful and powerful blocks to design websites.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/uag.svg';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('UAGB_Loader');
    }
}

IntegrationsController::register(Spectra::class);
