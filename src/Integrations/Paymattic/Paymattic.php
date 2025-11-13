<?php

namespace Dollie\SDK\Integrations\Paymattic;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'Paymattic',
    name: 'Paymattic',
    slug: 'paymattic',
    since: '1.0.0'
)]
/**
 * WP Simple Pay core integrations file
 *
 * @since 1.0.0
 */
class Paymattic extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'Paymattic';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Paymattic', 'dollie');
        $this->description = __('Paymattic is a WordPress Payment form plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wpsimplepay.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('WPPAYFORM_VERSION');
    }
}

IntegrationsController::register(Paymattic::class);
