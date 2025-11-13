<?php

namespace Dollie\SDK\Integrations\WPFusion;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WPFusion',
    name: 'WpFusion',
    slug: 'wp-fusion',
    since: '1.0.0'
)]
/**
 * WPFusion core integrations file
 *
 * @since 1.0.0
 */
class WPFusion extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WPFusion';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WP Fusion', 'dollie');
        $this->description = __('WP Fusion links WordPress with CRM.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wp-fusion.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        if (class_exists('WP_Fusion_Lite') || class_exists('WP_Fusion')) {
            return true;
        } else {
            return false;
        }
    }
}

IntegrationsController::register(WPFusion::class);
