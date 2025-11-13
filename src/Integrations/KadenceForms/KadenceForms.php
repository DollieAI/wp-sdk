<?php

namespace Dollie\SDK\Integrations\KadenceForms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'KadenceForms',
    name: 'KadenceForms',
    slug: 'kadence-forms',
    since: '1.0.0'
)]
/**
 * KadenceForms core integrations file
 *
 * @since 1.0.0
 */
class KadenceForms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'KadenceForms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('Kadence Forms', 'dollie');
        $this->description = __('A WordPress plugin that allows you to easily create contact or marketing form.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/kadenceforms.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('Kadence_Blocks_Form_Block');
    }
}

IntegrationsController::register(KadenceForms::class);
