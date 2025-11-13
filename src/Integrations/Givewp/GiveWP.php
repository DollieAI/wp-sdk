<?php

namespace Dollie\SDK\Integrations\Givewp;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use Give;

#[Integration(
    id: 'GiveWP',
    name: 'Givewp',
    slug: 'givewp',
    since: '1.0.0'
)]
/**
 * GiveWP integrations file
 *
 * @since 1.0.0
 */
class GiveWP extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'GiveWP';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('GiveWP', 'dollie');
        $this->description = __('GiveWP is an evolving WordPress donation plugin with a team that genuinely cares about advancing the democratization of generosity.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/givewp.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(Give::class);
    }
}

IntegrationsController::register(GiveWP::class);
