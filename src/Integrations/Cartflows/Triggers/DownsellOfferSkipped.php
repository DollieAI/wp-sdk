<?php

namespace Dollie\SDK\Integrations\Cartflows\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'cartflows_downsell_offer_skipped',
    label: 'Downsell Offer Skipped',
    since: '1.0.0'
)]
/**
 * DownsellOfferSkipped.
 * php version 5.6
 *
 * @category DownsellOfferSkipped
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * DownsellOfferSkipped
 *
 * @category DownsellOfferSkipped
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class DownsellOfferSkipped
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'CartFlows';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'cartflows_downsell_offer_skipped';

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
            'label' => __('Downsell Offer Skipped', 'dollie'),
            'action' => $this->trigger,
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int    $step_id Step ID.
     * @param object $order order object.
     * @param object $offer_product offer_product.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($step_id, $order, $offer_product)
    {
        $user_id = ap_get_current_user_id();
        // Ensure $order is an instance of WC_Order.
        if (! $order instanceof \WC_Order) {
            return;
        }
        if (is_int($user_id)) {
            $context = WordPress::get_user_context($user_id);
        }
        $context['order'] = $order->get_data();
        $context['downsell'] = $offer_product;
        $context['funnel_step_id'] = $step_id;
        $context['funnel_id'] = get_post_meta($step_id, 'wcf-flow-id', true);
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
DownsellOfferSkipped::get_instance();
