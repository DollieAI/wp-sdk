<?php

namespace Dollie\SDK\Integrations\FluentBoards\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'fbs_board_member_added',
    label: 'Board Member Added',
    since: '1.0.0'
)]
/**
 * BoardMemberAdded.
 * php version 5.6
 *
 * @category BoardMemberAdded
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * BoardMemberAdded
 *
 * @category BoardMemberAdded
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class BoardMemberAdded
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentBoards';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'fbs_board_member_added';

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
            'label' => __('Board Member Added', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'fluent_boards/board_member_added',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $board_id Board ID.
     * @param object $board_member Board Member.
     * @return void
     */
    public function trigger_listener($board_id, $board_member)
    {

        $context['board_id'] = $board_id;
        $context['board_member'] = $board_member;
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
BoardMemberAdded::get_instance();
