<?php

namespace Dollie\SDK\Integrations\Triggerbutton;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'TriggerButton',
    name: 'Triggerbutton',
    slug: 'triggerbutton',
    since: '1.0.0'
)]
/**
 * TriggerButton core integrations file
 *
 * @since   1.0.0
 */
class TriggerButton extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'TriggerButton';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Trigger Button', 'dollie');
        $this->description = __('A Trigger Button to complete the automation.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/triggerbutton.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return true;
    }
}

IntegrationsController::register(TriggerButton::class);
