<?php

namespace Dollie\SDK\Integrations\BeaverBuilder\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_submits_beaver_builder_form',
    label: 'User Submits Contact/Subscribe Form',
    since: '1.0.0'
)]
/**
 * UserSubmitsBeaverBuilderForm
 *
 * @category UserSubmitsBeaverBuilderForm
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class UserSubmitsBeaverBuilderForm
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'BeaverBuilder';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_submits_beaver_builder_form';

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
            'label' => __('User Submits Contact/Subscribe Form', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'suretriggers_bb_after_form_submit',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $context context Context Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($context)
    {
        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => ap_get_current_user_id(),
                'context' => $context,
            ]
        );
    }
}

UserSubmitsBeaverBuilderForm::get_instance();
