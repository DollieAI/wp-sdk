<?php

namespace Dollie\SDK\Integrations\Voxel\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'voxel_user_mentioned_in_comment',
    label: 'User Mentioned In Comment',
    since: '1.0.0'
)]
/**
 * VoxelUserMentionedInComment.
 * php version 5.6
 *
 * @category VoxelUserMentionedInComment
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * VoxelUserMentionedInComment
 *
 * @category VoxelUserMentionedInComment
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class VoxelUserMentionedInComment
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Voxel';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'voxel_user_mentioned_in_comment';

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
            'label' => __('User Mentioned In Comment', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'voxel/app-events/users/timeline/mentioned-in-comment',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $event Event.
     * @return void
     */
    public function trigger_listener($event)
    {
        if (! property_exists($event, 'comment')) {
            return;
        }
        $context['comment_by'] = WordPress::get_user_context($event->comment->get_user_id());
        $context['id'] = $event->comment->get_id();
        $context['status_id'] = $event->comment->get_status_id();
        $context['content'] = $event->comment->get_content();
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
VoxelUserMentionedInComment::get_instance();
