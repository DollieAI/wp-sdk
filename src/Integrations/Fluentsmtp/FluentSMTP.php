<?php

namespace Dollie\SDK\Integrations\Fluentsmtp;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'FluentSMTP',
    name: 'Fluentsmtp',
    slug: 'fluentsmtp',
    since: '1.0.0'
)]
/**
 * Fluent Form core integrations file
 *
 * @since 1.0.0
 */
class FluentSMTP extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'FluentSMTP';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('FluentSMTP', 'dollie');
        $this->description = __('FluentSMTP is the ultimate WP Mail Plugin that connects with your Email Service Provider natively and makes sure your emails are delivered.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/fluentSMTP.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return function_exists('fluentSmtpInit');
    }
}

IntegrationsController::register(FluentSMTP::class);
