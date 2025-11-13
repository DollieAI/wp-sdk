<?php

namespace Dollie\SDK\Integrations\Profilegrid;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'ProfileGrid',
    name: 'Profilegrid',
    slug: 'profilegrid',
    since: '1.0.0'
)]
/**
 * ProfileGrid core integrations file
 *
 * @since 1.0.0
 */
class ProfileGrid extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'ProfileGrid';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('ProfileGrid', 'dollie');
        $this->description = __('Create WordPress user profiles, groups, communities, paid memberships, directories, WooCommerce profiles, bbPress profiles, content restriction, sign-up pages, blog submissions, notifications, social activity and private messaging, beautiful threaded interface and a lot more!', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/profilegrid.svg';

    }

    /**
     * Profile Grid Group Details.
     *
     * @param int|string $group_id Group ID.
     *
     * @return array
     */
    public static function pg_group_details($group_id)
    {
        global $wpdb;
        $group = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}promag_groups WHERE id = %d", $group_id), ARRAY_A);

        return [
            'group_name' => $group['group_name'],
            'group_description' => $group['group_desc'],
        ];
    }

    /**
     * Is Plugin dependent plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('Profile_Magic');
    }
}

IntegrationsController::register(ProfileGrid::class);
