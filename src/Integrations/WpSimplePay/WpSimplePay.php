<?php

namespace Dollie\SDK\Integrations\WpSimplePay;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WpSimplePay',
    name: 'WpSimplePay',
    slug: 'wp-simple-pay',
    since: '1.0.0'
)]
/**
 * WP Simple Pay core integrations file
 *
 * @since 1.0.0
 */
class WpSimplePay extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WpSimplePay';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WP Simple Pay', 'dollie');
        $this->description = __('WP Simple Pay is a WordPress Payment form plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wpsimplepay.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('SIMPLE_PAY_PLUGIN_NAME');
    }
}

IntegrationsController::register(WpSimplePay::class);
