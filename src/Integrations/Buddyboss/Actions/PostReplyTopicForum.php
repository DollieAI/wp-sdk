<?php

namespace Dollie\SDK\Integrations\Buddyboss\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'bb_post_reply_topic_forum',
    label: 'Post Reply Topic In Forum',
    since: '1.0.0'
)]
/**
 * PostReplyTopicForum.
 * php version 5.6
 *
 * @category PostReplyTopicForum
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PostReplyTopicForum
 *
 * @category PostReplyTopicForum
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class PostReplyTopicForum extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BuddyBoss';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'bb_post_reply_topic_forum';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Post Reply Topic In Forum', 'dollie'),
            'action' => $this->action,
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user_id.
     * @param int   $automation_id automation_id.
     * @param array $fields fields.
     * @param array $selected_options selectedOptions.
     * @throws Exception Exception.
     *
     * @return bool|array
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! function_exists('bbp_insert_reply') || ! function_exists('bbp_get_reply')) {
            return [];
        }
        $forum_id = $selected_options['forum_id'];
        $topic_id = $selected_options['topic_id'];
        $reply_title = $selected_options['reply_title'];
        $reply_content = $selected_options['reply_content'];
        $reply_author = $selected_options['reply_creator'];

        if (is_email($reply_author)) {
            $user = get_user_by('email', $reply_author);
            if ($user) {
                $creator_id = $user->ID;
                $reply_id = bbp_insert_reply(
                    [
                        'post_parent' => $topic_id,
                        'post_title' => $reply_title,
                        'post_content' => $reply_content,
                        'post_author' => $creator_id,
                    ],
                    [
                        'forum_id' => $forum_id,
                        'topic_id' => $topic_id,
                    ]
                );

                return [
                    'forum_id' => $forum_id,
                    'forum_title' => get_the_title($forum_id),
                    'topic_id' => $topic_id,
                    'topic_title' => get_the_title($topic_id),
                    'reply_id' => $reply_id,
                    'reply' => bbp_get_reply($reply_id),
                    'reply_creator' => WordPress::get_user_context($creator_id),
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Reply user does not exist!!',
                ];
            }
        } else {
            return [
                'status' => 'error',
                'message' => 'Invalid Email!!',
            ];
        }
    }
}

PostReplyTopicForum::get_instance();
