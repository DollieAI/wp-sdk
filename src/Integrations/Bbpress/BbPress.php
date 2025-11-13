<?php

namespace Dollie\SDK\Integrations\Bbpress;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'bbPress',
    name: 'Bbpress',
    slug: 'bbpress',
    since: '1.0.0'
)]
class BbPress extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID of the integration
     *
     * @var string
     */
    protected $id = 'bbPress';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('bbPress', 'dollie');
        $this->description = __('Discussion forums for WordPress.', 'dollie');
        $this->icon_url = \DOLLIE_SDK_URL . 'assets/icons/bbpress.png';

        parent::__construct();
    }

    /**
     * Check plugin is installed.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('bbPress');
    }
}
IntegrationsController::register(BbPress::class);
