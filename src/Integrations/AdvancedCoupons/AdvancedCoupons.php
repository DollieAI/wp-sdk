<?php

namespace Dollie\SDK\Integrations\AdvancedCoupons;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'AdvancedCoupons',
    name: 'AdvancedCoupons',
    slug: 'advanced-coupons',
    since: '1.0.0'
)]
/**
 * Advanced Coupons core integrations file
 *
 * @since 1.0.0
 */
class AdvancedCoupons extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'AdvancedCoupons';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Advanced Coupons', 'dollie');
        $this->description = __('Advanced coupons for Woocommerce', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/AdvancedCoupons.svg';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('ACFWF');
    }
}

IntegrationsController::register(AdvancedCoupons::class);
