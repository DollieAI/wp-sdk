<?php

namespace Dollie\SDK\Integrations\Wordpress\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use WP_Post;

#[Trigger(
    id: 'wp_insert_comment',
    label: 'User submits a comment on a post',
    since: '1.0.0'
)]
/**
 * InsertComment.
 * php version 5.6
 *
 * @category InsertComment
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * InsertComment
 *
 * @category InsertComment
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class InsertComment
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
    public $trigger = 'wp_insert_comment';

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
            'event_name' => 'wp_insert_comment',
            'label' => __('User submits a comment on a post', 'dollie'),
            'action' => $this->trigger,
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int          $comment_id comment id.
     * @param object|array $comment comment.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($comment_id, $comment)
    {
        if (is_object($comment)) {
            $comment = get_object_vars($comment);
        }

        $post = get_post(absint($comment['comment_post_ID']));

        if (! $post instanceof WP_Post) {
            return;
        }

        $user_id = (int) $comment['user_id'];


        $context['comment_id'] = $comment_id;
        $context['comment'] = $comment['comment_content'];
        $context['comment_author'] = $comment['comment_author'];
        $context['comment_author_email'] = $comment['comment_author_email'];
        $context['comment_date'] = $comment['comment_date'];
        $context['post'] = $post->ID;
        $context['post_author'] = get_the_author_meta('display_name', (int) $post->post_author);
        $context['post_title'] = $post->post_title;
        $context['post_link'] = get_the_permalink($post->ID);
        $context = array_merge($context, WordPress::get_user_context($user_id));
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );

    }
}


InsertComment::get_instance();
