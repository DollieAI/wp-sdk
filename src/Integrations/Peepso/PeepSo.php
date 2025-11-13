<?php

namespace Dollie\SDK\Integrations\Peepso;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'PeepSo',
    name: 'Peepso',
    slug: 'peepso',
    since: '1.0.0'
)]
/**
 * PeepSo core integrations file
 *
 * @since 1.0.0
 */
class PeepSo extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'PeepSo';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('PeepSo', 'dollie');
        $this->description = __('PeepSo is a social network plugin for WordPress that allows you to quickly add a social network.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/peepso.svg';

    }

    /**
     * Get customer context data.
     *
     * @param int $post_id post details.
     * @param int $activity_id activity id.
     *
     * @return array
     */
    public static function get_pp_activity_context($post_id, $activity_id)
    {

        $pp_post = get_post($post_id);
        if (! $pp_post instanceof \WP_Post) {
            return [];
        }
        $context['post_id'] = $pp_post->ID;
        $context['activity_id'] = $activity_id;
        $context['post_author'] = $pp_post->post_author;
        $context['post_content'] = $pp_post->post_content;
        $context['post_title'] = $pp_post->post_title;
        $context['post_excerpt'] = $pp_post->post_excerpt;
        $context['post_status'] = $pp_post->post_status;
        $context['post_type'] = $pp_post->post_type;

        return $context;
    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('PeepSo');
    }
}

IntegrationsController::register(PeepSo::class);
