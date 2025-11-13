<?php

namespace Dollie\SDK\Integrations\PieForms\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_submits_pieforms',
    label: 'Form Submitted',
    since: '1.0.0'
)]
/**
 * UserSubmitsPieForms.
 * php version 5.6
 *
 * @category UserSubmitsPieForms
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserSubmitsPieForms
 *
 * @category UserSubmitsPieForms
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserSubmitsPieForms
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'PieForms';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_submits_pieforms';

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
            'label' => __('Form Submitted', 'dollie'),
            'action' => 'user_submits_pieforms',
            'common_action' => 'pie_forms_complete_entry_save',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 5,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $entry_id entry_id.
     * @param array $fields fields.
     * @param array $entry entry.
     * @param int   $form_id form_id.
     * @param array $form_data form_data.
     *
     * @return void
     */
    public function trigger_listener($entry_id, $fields, $entry, $form_id, $form_data)
    {
        if (empty($form_data)) {
            return;
        }

        $context = [];
        $context['form_id'] = (int) $form_id;

        foreach ($form_data as $value) {
            $context[$value['name']] = $value['value'];
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
UserSubmitsPieForms::get_instance();
