<?php

namespace Dollie\SDK\Integrations\Edd\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\EDD\EDD;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'edd_license_key_status_changed_to_active',
    label: 'License Key Status Changed To Active',
    since: '1.0.0'
)]
/**
 * LicenseKeyStatusChangedToActive.
 * php version 5.6
 *
 * @category LicenseKeyStatusChangedToActive
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * NewLicenseKey
 *
 * @category NewLicenseKey
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class LicenseKeyStatusChangedToActive
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'EDD';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'edd_license_key_status_changed_to_active';

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
            'label' => __('License Key Status Changed To Active', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'edd_sl_post_set_status',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int    $license_id License ID.
     * @param string $status Status.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($license_id, $status)
    {
        if ('active' !== $status) {
            return;
        }
        $context = EDD::edd_get_license_data($license_id);
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
LicenseKeyStatusChangedToActive::get_instance();
