<?php

namespace Dollie\SDK\Integrations\AdvancedCustomFields;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'AdvancedCustomFields',
    name: 'AdvancedCustomFields',
    slug: 'advanced-custom-fields',
    since: '1.0.0'
)]
/**
 * AdvancedCustomFields core integrations file
 *
 * @since   1.0.0
 */
class AdvancedCustomFields extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'AdvancedCustomFields';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('AdvancedCustomFields', 'dollie');
        $this->description = __('Advanced Custom Fields (ACF) helps you easily customize WordPress with powerful, professional and intuitive fields.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/AdvancedCustomFields.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('ACF');
    }
}

IntegrationsController::register(AdvancedCustomFields::class);
