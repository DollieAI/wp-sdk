<?php

namespace Dollie\SDK\Integrations\WSForm;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WSForm',
    name: 'WsForm',
    slug: 'ws-form',
    since: '1.0.0'
)]
/**
 * WSForm core integrations file
 *
 * @since 1.0.0
 */
class WSForm extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WSForm';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WS Form', 'dollie');
        $this->description = __('WS Form LITE is a powerful contact form builder plugin for WordPress.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/ws-form.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WS_Form');
    }
}

IntegrationsController::register(WSForm::class);
