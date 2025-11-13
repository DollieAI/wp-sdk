<?php

namespace Dollie\SDK\Integrations\Asgaros\Actions;

use AsgarosForum;
use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'asgaros_post_reply_in_topic',
    label: 'Post Reply in Topic',
    since: '1.0.0'
)]
/**
 * PostReply.
 * php version 5.6
 *
 * @category PostReply
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * PostReply
 *
 * @category PostReply
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class PostReply extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Asgaros';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'asgaros_post_reply_in_topic';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Post Reply in Topic', 'dollie'),
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
     * @return bool|array|void
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        if (! class_exists('AsgarosForum')) {
            return false;
        }
        $asgaros_forum = new AsgarosForum();
        $forum_id = $selected_options['forum_id'];
        $topic_id = $selected_options['topic_id'];
        $topic_description = $selected_options['post_description'];
        $author_id = $selected_options['author_id'];
        $post_id = $asgaros_forum->content->insert_post($topic_id, $forum_id, $topic_description, $author_id);
        $topic = (array) $asgaros_forum->content->get_topic($topic_id);
        $post = (array) $asgaros_forum->content->get_post($post_id);
        $user = WordPress::get_user_context($author_id);

        return array_merge($topic, $post, $user);
    }
}

PostReply::get_instance();
