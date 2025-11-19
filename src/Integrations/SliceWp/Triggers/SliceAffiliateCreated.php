<?php

namespace Dollie\SDK\Integrations\SliceWP\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Trigger(
    id: 'slicewp_new_affiliate',
    label: 'Affiliate Created',
    since: '1.0.0'
)]
/**
 * SliceAffiliateCreated.
 * php version 5.6
 *
 * @category AffiliateCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * SliceAffiliateCreated
 *
 * @category SliceAffiliateCreated
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class SliceAffiliateCreated
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'SliceWP';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'slicewp_new_affiliate';

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
     *
     * @return array
     */
    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Affiliate Created', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'slicewp_insert_affiliate',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $affiliate_id Affiliate ID.
     * @param array $affiliate_data Affiliate Data.
     * @throws Exception Exception.
     * @return void
     */
    public function trigger_listener($affiliate_id, $affiliate_data)
    {

        if (! function_exists('slicewp_get_affiliate')) {
            throw new Exception('Slicewp functions not found.');
        }
        $affiliate = slicewp_get_affiliate($affiliate_id);
        $user_id = $affiliate->get('user_id');
        $affiliate_data['id'] = $affiliate_id;
        $context = array_merge(
            ['user' => WordPress::get_user_context($user_id)],
            $affiliate_data
        );
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $user_id,
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
SliceAffiliateCreated::get_instance();
