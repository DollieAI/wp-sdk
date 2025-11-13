<?php

namespace Dollie\SDK\Integrations\Dollie\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\Dollie\Dollie;
use Dollie\SDK\Traits\SingletonLoader;

/**
 * SiteCreated Trigger
 */
#[Trigger(
    id: 'dollie_site_created',
    label: 'Site Created',
    since: '1.0.0'
)]

/**
 * Class SiteCreated
 */
class SiteCreated
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
    public $trigger = 'dollie_site_created';

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
            'label' => __('Site Created', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'dollie/container/launched',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $site_id Site post ID.
     * @param int $user_id User ID who created the site.
     *
     * @return void
     */
    public function trigger_listener($site_id, $user_id)
    {
        if (! $site_id) {
            return;
        }

        // Get user context
        $context = Dollie::get_user_context($user_id);

        // Get site context
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

SiteCreated::get_instance();
