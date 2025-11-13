<?php

namespace Dollie\SDK\Integrations\SupportPortalForSureCart;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'SupportPortalForSureCart',
    name: 'SupportPortalForSurecart',
    slug: 'support-portal-for-surecart',
    since: '1.0.0'
)]
/**
 * SupportPortalForSureCart core integrations file
 *
 * @since 1.0.0
 */
class SupportPortalForSureCart extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'SupportPortalForSureCart';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Support Portal For SureCart', 'dollie');
        $this->description = __('Support Portal For SureCart plugin allows customers to request support directly from their SureCart Customer Dashboard, seamlessly integrating a ticketing system with automated notifications and streamlined management within the SureCart interface.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/servicesforsurecart.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('SureCart') && defined('SURELYWP_SUPPORT_PORTAL');
    }
}

IntegrationsController::register(SupportPortalForSureCart::class);
