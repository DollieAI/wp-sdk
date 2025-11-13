<?php

namespace Dollie\SDK\Integrations\Suremail;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use SureMails\Loader;

#[Integration(
    id: 'SureMail',
    name: 'Suremail',
    slug: 'suremail',
    since: '1.0.0'
)]
/**
 * SureMail core integrations file
 *
 * @since 1.0.0
 */
class SureMail extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'SureMail';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('SureMail', 'dollie');
        $this->description = __('A simple yet powerful way to create modern forms for your website.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/Suremails.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(Loader::class);
    }
}

IntegrationsController::register(SureMail::class);
