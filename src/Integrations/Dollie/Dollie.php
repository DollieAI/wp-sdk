<?php

namespace Dollie\SDK\Integrations\Dollie;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

/**
 * Dollie integration class
 */
#[Integration(
    id: 'Dollie',
    name: 'Dollie',
    slug: 'dollie',
    since: '1.0.0'
)]

/**
 * Class Dollie
 */
class Dollie extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID of the integration
     *
     * @var string
     */
    protected $id = 'Dollie';

    /**
     * Dollie constructor.
     */
    public function __construct()
    {
        $this->name = __('Dollie', 'dollie');
        $this->description = __('Dollie WordPress hosting automation platform', 'dollie');
        $this->icon_url = DOLLIE_ASSETS_URL . 'img/dollie-icon.png';
    }

    /**
     * Get user context data.
     *
     * @param int $id User ID.
     *
     * @return array
     */
    public static function get_user_context($id)
    {
        $user = get_userdata($id);
        $context = [];

        if (! $user) {
            return $context;
        }

        $context['wp_user_id'] = $user->ID;
        $context['user_login'] = $user->user_login;
        $context['display_name'] = $user->display_name;
        $context['user_firstname'] = $user->user_firstname;
        $context['user_lastname'] = $user->user_lastname;
        $context['user_email'] = $user->user_email;
        $context['user_registered'] = $user->user_registered;
        $context['user_role'] = $user->roles;

        return $context;
    }

    /**
     * Get site context data.
     *
     * @param int $site_id Site post ID.
     *
     * @return array
     */
    public static function get_site_context($site_id)
    {
        $context = [];

        if (! $site_id) {
            return $context;
        }

        $site = get_post($site_id);

        if (! $site) {
            return $context;
        }

        $context['site_id'] = $site->ID;
        $context['site_name'] = $site->post_title;
        $context['site_url'] = get_permalink($site_id);
        $context['site_status'] = $site->post_status;
        $context['site_created'] = $site->post_date;
        $context['site_modified'] = $site->post_modified;
        $context['site_author_id'] = $site->post_author;

        // Get container object for additional data
        $container = dollie()->get_container($site_id);
        if (! is_wp_error($container)) {
            $context['container_url'] = $container->get_url();
            $context['container_domain'] = $container->get_custom_domain();
        }

        return $context;
    }

    /**
     * Check if Dollie plugin is installed.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return function_exists('dollie');
    }
}

IntegrationsController::register(Dollie::class);
