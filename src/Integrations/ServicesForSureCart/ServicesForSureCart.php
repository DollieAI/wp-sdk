<?php

namespace Dollie\SDK\Integrations\ServicesForSureCart;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'ServicesForSureCart',
    name: 'ServicesForSurecart',
    slug: 'services-for-surecart',
    since: '1.0.0'
)]
/**
 * ServicesForSureCart core integrations file
 *
 * @since 1.0.0
 */
class ServicesForSureCart extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'ServicesForSureCart';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Services For SureCart', 'dollie');
        $this->description = __('Services For SureCart plugin empowers you to sell services and custom deliverables with SureCart. Enjoy features like status and activity tracking, built-in messaging, and final delivery and approvals, all beautifully integrated directly into your website and customer dashboard.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/servicesforsurecart.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('SureCart') && defined('SURELYWP_SERVICES');
    }
}

IntegrationsController::register(ServicesForSureCart::class);
