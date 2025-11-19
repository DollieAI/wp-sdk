<?php

namespace Dollie\SDK\Integrations\Suremembers\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'suremember_updated_group',
    label: 'Group Updated',
    since: '1.0.0'
)]
/**
 * GroupUpdated.
 * php version 5.6
 *
 * @category GroupUpdated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GroupUpdated
 *
 * @category GroupUpdated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class GroupUpdated
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
    public $trigger = 'suremember_updated_group';

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
            'label' => __('Group Updated', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'suremembers_after_submit_form',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int $group_id The group id.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($group_id)
    {
        if (empty($group_id)) {
            return;
        }
        if (! check_ajax_referer('suremembers_submit_nonce', 'security')) {
            return;
        }
        $group = sanitize_post($_POST);

        $context['group'] = array_merge(WordPress::get_post_context($group_id), sanitize_post(isset($group['suremembers_post']) && is_array($group['suremembers_post']) ? $group['suremembers_post'] : []));
        $context['group_id'] = $group_id;
        unset($context['group']['ID']); //phpcs:ignore
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
GroupUpdated::get_instance();
