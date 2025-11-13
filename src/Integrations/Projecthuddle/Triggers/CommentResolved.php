<?php

namespace Dollie\SDK\Integrations\Projecthuddle\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use PH\Models\Post;

#[Trigger(
    id: 'ph_comment_resolved',
    label: 'Comment Marked As Resolved',
    since: '1.0.0'
)]
/**
 * CommentResolved.
 * php version 5.6
 *
 * @category CommentResolved
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CommentResolved
 *
 * @category CommentResolved
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class CommentResolved
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ProjectHuddle';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ph_comment_resolved';

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
            'label' => __('Comment Marked As Resolved', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'suretriggers_ph_after_comment_approval',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $comment Inserted or updated comment object.
     * @return void
     */
    public function trigger_listener($comment)
    {
        if (! class_exists('PH\Models\Post') || ! function_exists('ph_get_the_title')) {
            return;
        }
        global $wpdb;

        if (is_object($comment)) {
            $comment = get_object_vars($comment);
        }

        $id = $comment['ID'];

        $comment_result = $wpdb->get_row(
            $wpdb->prepare(
                'SELECT  wp_comments.comment_ID
					FROM wp_comments 
					WHERE comment_post_ID = %d
					LIMIT 0,1',
                $id
            ),
            ARRAY_A
        );

        $comment_id = $comment_result['comment_ID'];
        $meta_value = get_comment_meta($comment_id, 'project_id', true);
        $context['website_id'] = $meta_value;

        $context = $comment;
        $comment_item_id = get_comment_meta($comment_id, 'item_id');
        if (! empty($comment_item_id) && is_array($comment_item_id)) {
            $context['comment_item_id'] = $comment_item_id[0];
            $context['comment_item_page_title'] = get_the_title((int) $comment_item_id[0]);
            $context['comment_item_page_url'] = get_post_meta((int) $comment_item_id[0], 'page_url', true);
        }
        $context['ph_project_name'] = ph_get_the_title(Post::get($comment['comment_post_ID'])->parentsIds()['project']);
        $context['ph_commenter_name'] = $comment['comment_author'];
        if (is_int($comment['comment_post_ID'])) {
            $context['ph_project_type'] = (get_post_type($comment['comment_post_ID']) == 'ph-website') ? __('Website', 'dollie') : __('Mockup', 'dollie');
            $context['ph_action_status'] = get_post_meta($comment['comment_post_ID'], 'resolved', true) ? __('Resolved', 'dollie') : __('Unresolved', 'dollie');
            $context['ph_project_link'] = get_the_guid($comment['comment_post_ID']);
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
CommentResolved::get_instance();
