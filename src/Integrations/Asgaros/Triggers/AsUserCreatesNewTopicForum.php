<?php

namespace Dollie\SDK\Integrations\Asgaros\Triggers;

use AsgarosForum;
use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'asgaros_user_creates_new_topic_forum',
    label: 'User Creates New Topic in Forum',
    since: '1.0.0'
)]
/**
 * AsUserCreatesNewTopicForum.
 * php version 5.6
 *
 * @category AsUserCreatesNewTopicForum
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AsUserCreatesNewTopicForum
 *
 * @category AsUserCreatesNewTopicForum
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AsUserCreatesNewTopicForum
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Asgaros';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'asgaros_user_creates_new_topic_forum';

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
            'label' => __('User Creates New Topic in Forum', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'asgarosforum_after_add_topic_submit',
            'function' => [$this, 'trigger_listener'],
            'priority' => 5,
            'accepted_args' => 6,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $post_id Post.
     * @param int $topic_id Topic.
     * @param string  $subject Topic.
     * @param string  $content Content.
     * @param string  $link Link.
     * @param int $author_id author id.
     * @return void
     */
    public function trigger_listener($post_id, $topic_id, $subject, $content, $link, $author_id)
    {
        if (! class_exists('AsgarosForum')) {
            return;
        }
        $context = [];
        $asgaros_forum = new AsgarosForum();
        if (! isset($post_id)) {
            return;
        }

        $topic = $asgaros_forum->content->get_topic($topic_id);
        $forum_id = $topic->parent_id;
        $context['topic_id'] = $topic_id;
        $context['post_id'] = $post_id;

        $context['forum_id'] = $forum_id;
        $context['forum'] = $asgaros_forum->content->get_forum($forum_id);
        $context['topic'] = $asgaros_forum->content->get_topic($topic_id);
        $context['post'] = $asgaros_forum->content->get_post($post_id);
        $context['author'] = WordPress::get_user_context($author_id);
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
AsUserCreatesNewTopicForum::get_instance();
