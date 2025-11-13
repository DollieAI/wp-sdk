<?php

namespace Dollie\SDK\Integrations\MailMint;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'MailMint',
    name: 'MailMint',
    slug: 'mail-mint',
    since: '1.0.0'
)]
/**
 * MailMint core integrations file
 *
 * @since 1.0.0
 */
class MailMint extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'MailMint';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('MailMint', 'dollie');
    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('MAILMINT');
    }
}

IntegrationsController::register(MailMint::class);
