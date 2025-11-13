<?php

namespace Dollie\SDK\Integrations\ThriveLeads\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'tl_registration_form_submitted',
    label: 'Registration Form Submitted',
    since: '1.0.0'
)]
/**
 * RegistrationFormSubmitted.
 * php version 5.6
 *
 * @category RegistrationFormSubmitted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RegistrationFormSubmitted
 *
 * @category RegistrationFormSubmitted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class RegistrationFormSubmitted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ThriveLeads';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'tl_registration_form_submitted';

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
            'label' => __('Registration Form Submitted', 'dollie'),
            'action' => 'tl_registration_form_submitted',
            'common_action' => 'thrive_register_form_through_wordpress_user',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $user_id User ID.
     * @param array $data Form Data.
     *
     * @return void
     */
    public function trigger_listener($user_id, $data)
    {
        if (! empty($data)) {
            $data['user_id'] = $user_id;
            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $data,
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
RegistrationFormSubmitted::get_instance();
