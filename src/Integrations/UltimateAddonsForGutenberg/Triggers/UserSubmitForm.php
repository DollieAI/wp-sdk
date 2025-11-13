<?php

namespace Dollie\SDK\Integrations\UltimateAddonsForGutenberg\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_submits_spectraform',
    label: 'User Submits Form',
    since: '1.0.0'
)]
/**
 * UserSubmitsUAGForm.
 *
 * @category UserSubmitsUAGForm
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserSubmitsUAGForm
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Spectra';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_submits_spectraform';

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
            'label' => __('User Submits Form', 'dollie'),
            'action' => 'user_submits_spectraform',
            'common_action' => 'uagb_form_success',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $form_data Form submitted data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($form_data)
    {

        if (empty($form_data)) {
            return;
        }
        $user_id = ap_get_current_user_id();
        $form_data['spectra_form'] = $form_data['id'];
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'user_id' => $user_id,
                'context' => $form_data,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
UserSubmitsUAGForm::get_instance();
