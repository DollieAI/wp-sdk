<?php

namespace Dollie\SDK\Integrations\Mailpoet;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use MailPoet\Plugin;

#[Integration(
    id: 'MailPoet',
    name: 'Mailpoet',
    slug: 'mailpoet',
    since: '1.0.0'
)]
/**
 * MailPoet core integrations file
 *
 * @since   1.0.0
 */
class MailPoet extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'MailPoet';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('MailPoet', 'dollie');
        $this->description = __('A WordPress plugin for emails and newletters.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/mailpoet.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('\MailPoet\Config\Activator');
    }
}

IntegrationsController::register(MailPoet::class);
