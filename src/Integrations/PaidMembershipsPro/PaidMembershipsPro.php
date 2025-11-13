<?php

namespace Dollie\SDK\Integrations\PaidMembershipsPro;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'PaidMembershipsPro',
    name: 'PaidMembershipsPro',
    slug: 'paid-memberships-pro',
    since: '1.0.0'
)]
/**
 * PaidMembershipsPro core integrations file
 *
 * @since 1.0.0
 */
class PaidMembershipsPro extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'PaidMembershipsPro';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('PaidMembershipsPro', 'dollie');
        $this->description = __('A tool that help to start, manage and grow membership.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/paidmembershipspro.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('PMPRO_BASE_FILE');
    }
}

IntegrationsController::register(PaidMembershipsPro::class);
