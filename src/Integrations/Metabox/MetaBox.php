<?php

namespace Dollie\SDK\Integrations\Metabox;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'MetaBox',
    name: 'Metabox',
    slug: 'metabox',
    since: '1.0.0'
)]
/**
 * MetaBox core integrations file
 *
 * @since   1.0.0
 */
class MetaBox extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'MetaBox';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('MetaBox', 'dollie');
        $this->description = __('Meta Box is a framework that helps you create custom post types and custom fields quickly and easily.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/MetaBox.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return function_exists('rwmb_get_object_fields');
    }
}

IntegrationsController::register(MetaBox::class);
