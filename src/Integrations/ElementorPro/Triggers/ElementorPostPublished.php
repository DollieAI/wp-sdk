<?php

namespace Dollie\SDK\Integrations\ElementorPro\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use WP_Post;

#[Trigger(
    id: 'elementor_post_published',
    label: 'Post Published with Elementor',
    since: '1.0.0'
)]
/**
 * ElementorPostPublished.
 * php version 5.6
 *
 * @category ElementorPostPublished
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ElementorPostPublished
 *
 * @category ElementorPostPublished
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ElementorPostPublished
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ElementorPro';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'elementor_post_published';

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
            'label' => __('Post Published with Elementor', 'dollie'),
            'action' => 'new_user_submits_elementor_form',
            'common_action' => 'transition_post_status',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int     $new_status checks new status.
     * @param int     $old_status checks old status.
     * @param WP_Post $post post object.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($new_status, $old_status, $post)
    {

        // if post is not published with Elementor.
        $created_by_elem = get_post_meta($post->ID, '_elementor_edit_mode', true);
        if (empty($created_by_elem)) {
            return;
        }

        if ($old_status === $new_status) {
            return;
        }

        if ('publish' === $new_status && ! wp_is_post_revision($post->ID) && ! wp_is_post_autosave($post->ID)) {
            $context = [
                'post_id' => $post->ID,
                'post' => $post,
                'post_type' => $post->post_type,
            ];

            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

ElementorPostPublished::get_instance();
