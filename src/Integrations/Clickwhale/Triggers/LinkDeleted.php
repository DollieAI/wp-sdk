<?php

namespace Dollie\SDK\Integrations\Clickwhale\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'cw_link_deleted',
    label: 'Link Deleted',
    since: '1.0.0'
)]
/**
 * LinkDeleted.
 * php version 5.6
 *
 * @category LinkDeleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * LinkDeleted
 *
 * @category LinkDeleted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class LinkDeleted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ClickWhale';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'cw_link_deleted';

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
            'label' => __('Link Deleted', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'clickwhale_link_deleted',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $ids Array of deleted link IDs.
     * @return void
     */
    public function trigger_listener($ids)
    {
        if (empty($ids) || ! is_array($ids)) {
            return;
        }

        foreach ($ids as $link_id) {
            $context = [
                'link_id' => $link_id,
                'deleted_at' => current_time('mysql'),
            ];

            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
LinkDeleted::get_instance();
