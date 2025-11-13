<?php

namespace Dollie\SDK\Integrations\Metform;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'MetForm',
    name: 'Metform',
    slug: 'metform',
    since: '1.0.0'
)]
/**
 * Met Form core integrations file
 *
 * @since 1.0.0
 */
class MetForm extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'MetForm';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('MetForm', 'dollie');
        $this->description = __('MetForm is a WordPress Form Builder.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/metform.svg';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(\MetForm\Plugin::class);
    }
}

IntegrationsController::register(MetForm::class);
