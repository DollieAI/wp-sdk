<?php

namespace Dollie\SDK\Integrations\Edd\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\EDD\EDD;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'edd_new_license_key',
    label: 'New License Key',
    since: '1.0.0'
)]
/**
 * NewLicenseKey.
 * php version 5.6
 *
 * @category NewLicenseKey
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
class NewLicenseKey
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
    public $trigger = 'edd_new_license_key';

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
            'label' => __('New License Key', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'edd_sl_store_license',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int    $license_id License ID.
     * @param int    $download_id Download ID.
     * @param int    $payment_id Payment ID.
     * @param string $type Type.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($license_id, $download_id, $payment_id, $type)
    {

        $context = EDD::edd_get_license_data($license_id, $download_id, $payment_id);
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
NewLicenseKey::get_instance();
