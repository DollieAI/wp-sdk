<?php

namespace Dollie\SDK\Integrations\Fluentcommunity\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'fc_comment_updated',
    label: 'CommentUpdated',
    since: '1.0.0'
)]
/**
 * CommentUpdated.
 * php version 5.6
 *
 * @category CommentUpdated
 * @author   BSF
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CommentUpdated
 *
 * @category CommentUpdated
 * @since    1.0.0
 */
class CommentUpdated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentCommunity';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'fc_comment_updated';

    /**
     * Constructor
     *
     * @since 1.0.0
     */
    public function __construct()
    {
        add_action('fluent_community/comment_updated', [$this, 'trigger_listener'], 10, 2);
    }

    /**
     * Trigger listener.
     *
     * @param object $comment The newly created comment object.
     * @param object $feed The newly created feed object.
     * @return void
     */
    public function trigger_listener($comment, $feed)
    {

        if (empty($comment)) {
            return;
        }

        // Prepare context with the course object.
        $context = [
            'comment' => $comment,
        ];

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $context,
            ]
        );
    }
}

CommentUpdated::get_instance();
