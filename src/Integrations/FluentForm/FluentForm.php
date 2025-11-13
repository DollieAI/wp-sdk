<?php

namespace Dollie\SDK\Integrations\FluentForm;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'FluentForm',
    name: 'FluentForm',
    slug: 'fluent-form',
    since: '1.0.0'
)]
/**
 * Fluent Form core integrations file
 *
 * @since 1.0.0
 */
class FluentForm extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'FluentForm';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Fluent Form', 'dollie');
        $this->description = __('Fluent Form is a WordPress Form Builder.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/fluentform.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('FLUENTFORM');
    }
}

IntegrationsController::register(FluentForm::class);
