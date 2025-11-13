<?php

namespace Dollie\SDK\Integrations\EasyAffiliate;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'EasyAffiliate',
    name: 'EasyAffiliate',
    slug: 'easy-affiliate',
    since: '1.0.0'
)]
/**
 * EasyAffiliate core integrations file
 *
 * @since 1.0.0
 */
class EasyAffiliate extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'EasyAffiliate';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Easy Affiliate', 'dollie');
        $this->description = __('Affiliate Program Plugin for WordPress', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/easyaffiliate.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('ESAF_PLUGIN_SLUG');
    }
}

IntegrationsController::register(EasyAffiliate::class);
