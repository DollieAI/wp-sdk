<?php

namespace Dollie\SDK\Integrations\Suremembers\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'suremember_user_removed_from_group',
    label: 'User Removed',
    since: '1.0.0'
)]
/**
 * UserRemovedFromGroup.
 * php version 5.6
 *
 * @category UserRemovedFromGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserRemovedFromGroup
 *
 * @category UserRemovedFromGroup
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserRemovedFromGroup
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'SureMembers';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'suremember_user_removed_from_group';

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
            'label' => __('User Removed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'suremembers_after_access_revoke',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener.
     *
     * @param int   $user_id user id.
     * @param array $access_group_ids access group id.
     * @return void
     */
    public function trigger_listener($user_id, $access_group_ids)
    {
        if (empty($user_id)) {
            return;
        }

        $context = '';

        foreach ($access_group_ids as $group_id) {
            $context = WordPress::get_user_context($user_id);
            $context['group'] = WordPress::get_post_context($group_id);
            $context['group_id'] = $group_id;
            unset($context['group']['ID']);
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'user_id' => $user_id,
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
UserRemovedFromGroup::get_instance();
