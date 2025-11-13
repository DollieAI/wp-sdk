<?php

namespace Dollie\SDK\Integrations\EasyAffiliate\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'affiliate_added',
    label: 'Affiliate Added',
    since: '1.0.0'
)]
/**
 * AffiliateAdded.
 * php version 5.6
 *
 * @category AffiliateAdded
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AffiliateAdded
 *
 * @category AffiliateAdded
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class AffiliateAdded
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'EasyAffiliate';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'affiliate_added';

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
            'label' => __('Affiliate Added', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'esaf_event_affiliate-added',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object $event Event.
     * @return void|array
     */
    public function trigger_listener($event)
    {

        if (! class_exists('EasyAffiliate\Models\User')) {
            return;
        }

        if (! is_object($event) || ! property_exists($event, 'rec')) {
            return;
        }

        if (! is_object($event->rec) || ! property_exists($event->rec, 'evt_id_type') || ! property_exists($event->rec, 'evt_id')) {
            return;
        }

        if (empty($event->rec->evt_id_type) && empty($event->rec->evt_id) && 'user' !== $event->rec->evt_id_type) {
            return;
        }

        // Check if the properties exist before accessing them.
        if (property_exists($event->rec, 'evt_id')) {
            $data = new \EasyAffiliate\Models\User($event->rec->evt_id);
            $context = get_object_vars($data->rec);
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
AffiliateAdded::get_instance();
