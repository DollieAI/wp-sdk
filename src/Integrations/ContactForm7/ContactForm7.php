<?php

namespace Dollie\SDK\Integrations\ContactForm7;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'ContactForm7',
    name: 'ContactForm7',
    slug: 'contact-form7',
    since: '1.0.0'
)]
/**
 * ContactForm7 core integrations file
 *
 * @since 1.0.0
 */
class ContactForm7 extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'ContactForm7';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Contact Form7', 'dollie');
        $this->description = __('A WordPress plugin of form submission', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/ContactForm7.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WPCF7');
    }
}

IntegrationsController::register(ContactForm7::class);
