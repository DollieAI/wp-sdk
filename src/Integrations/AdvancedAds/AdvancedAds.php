<?php

namespace Dollie\SDK\Integrations\AdvancedAds;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'AdvancedAds',
    name: 'AdvancedAds',
    slug: 'advanced-ads',
    since: '1.0.0'
)]
/**
 * AdvancedAds core integrations file
 *
 * @since 1.0.0
 */
class AdvancedAds extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'AdvancedAds';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Advanced Ads', 'dollie');
        $this->description = __('A Powerful WordPress Ad Management Plugin. Advanced Ads is a great plugin that makes it easier to manage your ads.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/advanced-ads.svg';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('Advanced_Ads');
    }
}

IntegrationsController::register(AdvancedAds::class);
