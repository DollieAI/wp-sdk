<?php

namespace Dollie\SDK\Integrations\Affiliatewp;

use Affiliate_WP;
use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'AffiliateWP',
    name: 'Affiliatewp',
    slug: 'affiliatewp',
    since: '1.0.0'
)]
/**
 * AffiliateWP core integrations file
 *
 * @since 1.0.0
 */
class AffiliateWP extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'AffiliateWP';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('AffiliateWP', 'dollie');
        $this->description = __('Affiliate Plugin for WordPress.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/affiliatewp.svg';

    }

    /**
     * Is Plugin dependent plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(Affiliate_WP::class);
    }
}

IntegrationsController::register(AffiliateWP::class);
