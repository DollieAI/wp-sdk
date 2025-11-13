<?php

namespace Dollie\SDK\Integrations\Groundhogg;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'Groundhogg',
    name: 'Groundhogg',
    slug: 'groundhogg',
    since: '1.0.0'
)]
/**
 * Groundhogg core integrations file
 *
 * @since 1.0.0
 */
class Groundhogg extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'Groundhogg';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Groundhogg', 'dollie');
        $this->description = __('Groundhogg is the best WordPress CRM & Marketing Automation plugin. Create funnels, email campaigns, newsletters, marketing automation.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/Groundhogg.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('GROUNDHOGG_VERSION');
    }
}

IntegrationsController::register(Groundhogg::class);
