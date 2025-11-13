<?php

namespace Dollie\SDK\Integrations\GravityForm\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_register_gravityform',
    label: 'User Register with Gravity Form',
    since: '1.0.0'
)]
/**
 * UserRegisterGravityForm.
 * php version 5.6
 *
 * @category UserRegisterGravityForm
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserRegisterGravityForm
 *
 * @category UserRegisterGravityForm
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserRegisterGravityForm
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'GravityForms';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_register_gravityform';

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
            'label' => __('User Register with Gravity Form', 'dollie'),
            'action' => 'user_register_gravityform',
            'common_action' => 'gform_user_registered',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 4,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param int     $user_id           The form object for the entry.
     * @param int $feed     The entry ID.
     * @param array   $entry The entry object before being updated.
     * @param array   $password The entry object before being updated.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($user_id, $feed, $entry, $password)
    {

        $context['gravity_form'] = (int) $entry['form_id'];
        $context['entry_id'] = $entry['id'];
        $context['user_ip'] = $entry['ip'];
        $context['entry_source_url'] = $entry['source_url'];
        $context['entry_submission_date'] = $entry['date_created'];
        $context['user'] = WordPress::get_user_context($user_id);

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
UserRegisterGravityForm::get_instance();
