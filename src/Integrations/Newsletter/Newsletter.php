<?php

namespace Dollie\SDK\Integrations\Newsletter;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'Newsletter',
    name: 'Newsletter',
    slug: 'newsletter',
    since: '1.0.0'
)]
/**
 * Newsletter core integrations file
 *
 * @since 1.0.0
 */
class Newsletter extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'Newsletter';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Newsletter', 'dollie');
        $this->description = __('Newsletter is a powerful yet simple email creation tool that helps you get in touch with your subscribers and engage them with your own content.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/newsletter.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        if (defined('NEWSLETTER_VERSION')) {
            return true;
        } else {
            return false;
        }
    }
}

IntegrationsController::register(Newsletter::class);
