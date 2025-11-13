<?php

namespace Dollie\SDK\Integrations\Suremembers;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use SureMembers\Plugin_Loader;

#[Integration(
    id: 'SureMembers',
    name: 'Suremembers',
    slug: 'suremembers',
    since: '1.0.0'
)]
/**
 * SureMembers core integrations file
 *
 * @since 1.0.0
 */
class SureMembers extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'SureMembers';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('SureMembers', 'dollie');
        $this->description = __('A simple yet powerful way to add content restriction to your website.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/suremembers.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(Plugin_Loader::class);
    }
}

IntegrationsController::register(SureMembers::class);
