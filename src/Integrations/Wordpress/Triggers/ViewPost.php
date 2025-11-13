<?php

namespace Dollie\SDK\Integrations\Wordpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use WP_Post;

#[Trigger(
    id: 'wp_view_post',
    label: 'User views any post',
    since: '1.0.0'
)]
/**
 * ViewPost.
 * php version 5.6
 *
 * @category ViewPost
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ViewPost
 *
 * @category ViewPost
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ViewPost
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WordPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wp_view_post';

    /**
     * Constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register action.
     *
     * @param array $triggers trigger data.
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('User views any post', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'template_redirect',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener.
     *
     * @return void
     */
    public function trigger_listener()
    {

        if (! is_singular()) {
            return;
        }

        $post_id = get_queried_object_id();
        $user_id = ap_get_current_user_id();
        $context = WordPress::get_post_context($post_id);
        $context = array_merge($context, WordPress::get_user_context($user_id));
        $context['post'] = $post_id;
        $featured_image = wp_get_attachment_image_src((int) get_post_thumbnail_id($post_id), 'full');
        if (! empty($featured_image) && is_array($featured_image)) {
            $context['featured_image'] = $featured_image[0];
        }
        $post = get_post($post_id);
        if ($post instanceof WP_Post) {
            $taxonomies = get_object_taxonomies($post, 'objects');
            if (! empty($taxonomies) && is_array($taxonomies)) {
                foreach ($taxonomies as $taxonomy => $taxonomy_object) {
                    $terms = get_the_terms($post_id, $taxonomy);
                    if (! empty($terms) && is_array($terms)) {
                        foreach ($terms as $term) {
                            $context[$taxonomy] = $term->name;
                        }
                    }
                }
            }
        }
        $custom_metas = get_post_meta($post_id);
        $context['custom_metas'] = $custom_metas;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );

    }
}


ViewPost::get_instance();
