<?php

namespace Dollie\SDK\Integrations\Buddypress\Triggers;

use BP_Activity_Activity;
use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_posts_activity_stream',
    label: 'A user posts activity to their stream',
    since: '1.0.0'
)]
/**
 * UserPostsActivityStream.
 * php version 5.6
 *
 * @category UserPostsActivityStream
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserPostsActivityStream
 *
 * @category UserPostsActivityStream
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserPostsActivityStream
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BuddyPress';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_posts_activity_stream';

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
            'label' => __('A user posts activity to their stream', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'bp_activity_posted_update',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $content Content.
     * @param int $user_id User ID.
     * @param int $activity_id Activity ID.
     * @return void
     */
    public function trigger_listener($content, $user_id, $activity_id)
    {

        $context['content'] = $content;
        $context['user'] = WordPress::get_user_context($user_id);
        if (class_exists('BP_Activity_Activity')) {
            $context['activity'] = new BP_Activity_Activity($activity_id);
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
UserPostsActivityStream::get_instance();
