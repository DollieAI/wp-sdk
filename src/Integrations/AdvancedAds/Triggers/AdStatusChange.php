<?php

namespace Dollie\SDK\Integrations\AdvancedAds\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ad_status_change',
    label: 'Ad Status Changed',
    since: '1.0.0'
)]
/**
 * AdStatusChange.
 * php version 5.6
 *
 * @category AdStatusChange
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AdStatusChange
 *
 * @category AdStatusChange
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AdStatusChange
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'AdvancedAds';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ad_status_change';

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
            'label' => __('Ad Status Changed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => ['transition_post_status'],
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param string $ad_new_status Ad New Status.
     * @param object $ad_old_status Ad Old Status.
     * @param object $ad Data.
     * @return void
     */
    public function trigger_listener($ad_new_status, $ad_old_status, $ad)
    {

        if (property_exists($ad, 'ID')) {
            if ('' == $ad->ID) {
                return;
            }
        }

        if (property_exists($ad, 'ID')) {
            $context = WordPress::get_post_context($ad->ID);
            $context['ad_id'] = $ad->ID;
            $context['ad_old_status'] = $ad_old_status;
            $context['ad_new_status'] = $ad_new_status;

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
AdStatusChange::get_instance();
