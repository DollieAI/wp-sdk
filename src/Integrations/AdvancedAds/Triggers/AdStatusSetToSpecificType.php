<?php

namespace Dollie\SDK\Integrations\AdvancedAds\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ad_status_set_to_specific_type',
    label: 'Ad Status Set to Specific Type',
    since: '1.0.0'
)]
/**
 * AdStatusSetToSpecificType.
 * php version 5.6
 *
 * @category AdStatusSetToSpecificType
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AdStatusSetToSpecificType
 *
 * @category AdStatusSetToSpecificType
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AdStatusSetToSpecificType
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
    public $trigger = 'ad_status_set_to_specific_type';

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
            'label' => __('Ad Status Set to Specific Type', 'dollie'),
            'action' => $this->trigger,
            'common_action' => [
                'advanced-ads-ad-status-draft-to-pending',
                'advanced-ads-ad-status-draft-to-publish',
                'advanced-ads-ad-status-draft-to-advanced_ads_expired',
                'advanced-ads-ad-status-pending-to-draft',
                'advanced-ads-ad-status-pending-to-publish',
                'advanced-ads-ad-status-pending-to-advanced_ads_expired',
                'advanced-ads-ad-status-publish-to-draft',
                'advanced-ads-ad-status-publish-to-pending',
                'advanced-ads-ad-status-publish-to-advanced_ads_expired',
                'advanced-ads-ad-status-advanced_ads_expired-to-publish',
                'advanced-ads-ad-status-advanced_ads_expired-to-pending',
                'advanced-ads-ad-status-advanced_ads_expired-to-draft',
            ],
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $ad Data.
     * @return void
     */
    public function trigger_listener($ad)
    {

        if (property_exists($ad, 'id')) {
            if ('' == $ad->id) {
                return;
            }
        }

        if (property_exists($ad, 'id')) {
            $context = WordPress::get_post_context($ad->id);
            $context['ad_id'] = $ad->id;
            if (property_exists($ad, 'status')) {
                $context['ad_status'] = $ad->status;

                AutomationController::dollie_trigger_handle_trigger(
                    [
                        'trigger' => $this->trigger,
                        'context' => $context,
                    ]
                );
            }
        }
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
AdStatusSetToSpecificType::get_instance();
