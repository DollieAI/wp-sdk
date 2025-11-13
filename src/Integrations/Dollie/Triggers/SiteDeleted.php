<?php

namespace Dollie\SDK\Integrations\Dollie\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\Dollie\Dollie;
use Dollie\SDK\Traits\SingletonLoader;

/**
 * SiteDeleted Trigger
 */
#[Trigger(
    id: 'dollie_site_deleted',
    label: 'Site Deleted',
    since: '1.0.0'
)]

/**
 * Class SiteDeleted
 */
class SiteDeleted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Dollie';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'dollie_site_deleted';

    /**
     * Constructor
     */
    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
    }

    /**
     * Register trigger.
     *
     * @param array $triggers triggers.
     *
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Site Deleted', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'before_delete_post',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $site_id Site post ID being deleted.
     *
     * @return void
     */
    public function trigger_listener($site_id)
    {
        // Only fire for Dollie container post types
        if (get_post_type($site_id) !== 'container') {
            return;
        }

        $user_id = get_current_user_id();

        // Get user context
        $context = Dollie::get_user_context($user_id);

        // Get site context before it's deleted
        $site_context = Dollie::get_site_context($site_id);

        // Merge contexts
        $context = array_merge($context, $site_context);

        // Fire the trigger
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $user_id,
                'site_id' => $site_id,
                'context' => $context,
            ]
        );
    }
}

SiteDeleted::get_instance();
