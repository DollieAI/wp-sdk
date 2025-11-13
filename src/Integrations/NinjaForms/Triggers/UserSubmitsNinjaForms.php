<?php

namespace Dollie\SDK\Integrations\NinjaForms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_submits_ninjaforms',
    label: 'User Submits Form',
    since: '1.0.0'
)]
/**
 * UserSubmitsNinjaForms.
 * php version 5.6
 *
 * @category UserSubmitsNinjaForms
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserSubmitsNinjaForms
 *
 * @category UserSubmitsNinjaForms
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserSubmitsNinjaForms
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'NinjaForms';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_submits_ninjaforms';

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
            'action' => 'user_submits_ninjaforms',
            'common_action' => 'ninja_forms_after_submission',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $data data.
     *
     * @return void
     */
    public function trigger_listener($data)
    {
        if (empty($data) || ! isset($data['form_id']) || ! isset($data['fields_by_key'])) {
            return;
        }

        $context = [];
        $context['form_id'] = $data['form_id'];

        foreach ($data['fields_by_key'] as $key => $field) {
            $context[$key] = $field['value'];
        }

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
UserSubmitsNinjaForms::get_instance();
