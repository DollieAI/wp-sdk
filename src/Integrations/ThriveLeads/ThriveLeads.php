<?php

namespace Dollie\SDK\Integrations\ThriveLeads;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'ThriveLeads',
    name: 'ThriveLeads',
    slug: 'thrive-leads',
    since: '1.0.0'
)]
/**
 * ThriveLeads core integrations file
 *
 * @since 1.0.0
 */
class ThriveLeads extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'ThriveLeads';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Thrive Leads', 'dollie');
        $this->description = __('Lead generation plugin for WordPress.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/thriveleads.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('TVE_LEADS_PATH');
    }
}

IntegrationsController::register(ThriveLeads::class);
