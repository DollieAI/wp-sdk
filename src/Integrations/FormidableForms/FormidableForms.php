<?php

namespace Dollie\SDK\Integrations\FormidableForms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'FormidableForms',
    name: 'FormidableForms',
    slug: 'formidable-forms',
    since: '1.0.0'
)]
/**
 * FormidableForms core integrations file
 *
 * @since 1.0.0
 */
class FormidableForms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'FormidableForms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('FormidableForms', 'dollie');
        $this->description = __('A WordPress form builder plugin that lets you build single or multi-page contact forms. ', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/formidableforms.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('FrmHooksController');
    }
}

IntegrationsController::register(FormidableForms::class);
