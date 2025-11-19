<?php

namespace Dollie\SDK\Integrations\MasterStudyLms;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'MasterStudyLms',
    name: 'MasterstudyLms',
    slug: 'masterstudy-lms',
    since: '1.0.0'
)]
/**
 * MasterStudyLms core integrations file
 *
 * @since   1.0.0
 */
class MasterStudyLms extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'MasterStudyLms';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('MasterStudyLms', 'dollie');
        $this->description = __('A WordPress LMS Plugin.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/masterstudylms.png';
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        if (in_array('masterstudy-lms-learning-management-system/masterstudy-lms-learning-management-system.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            // Plugin is active and installed.
            return true;
        } else {
            // Plugin is not active or installed.
            return false;
        }
    }
}

IntegrationsController::register(MasterStudyLms::class);
