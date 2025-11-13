<?php

namespace Dollie\SDK\Integrations\ElementorPro\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'elementor_submit_form_specific_value',
    label: 'Form Submitted with Specific Field Value',
    since: '1.0.0'
)]
/**
 * ElementorSubmitFormSpecificValue.
 * php version 5.6
 *
 * @category ElementorSubmitFormSpecificValue
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ElementorSubmitFormSpecificValue
 *
 * @category ElementorSubmitFormSpecificValue
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ElementorSubmitFormSpecificValue
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ElementorPro';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'elementor_submit_form_specific_value';

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
            'label' => __('Form Submitted with Specific Field Value', 'dollie'),
            'action' => 'new_user_submits_elementor_form',
            'common_action' => 'elementor_pro/forms/new_record',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param object|array $record The record submitted.
     * @param array        $handler The Ajax Handler component.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($record, $handler)
    {
        if (empty($record)) {
            return;
        }

        if (is_object($record) && method_exists($record, 'get')) {
            foreach ($record->get('fields') as $key => $value) {
                $context['field_id'] = $value['id'];
                $context['form_id'] = $record->get('form_settings')['form_post_id'] . '_' . $record->get('form_settings')['id'];
                $context['form_name'] = $record->get('form_settings')['form_name'];
                $context['field_value'] = $value['value'];
                $data = [];
                foreach ($record->get('fields') as $key => $value) {
                    $data[str_replace(' ', '_', strtolower(isset($value['title']) ? $value['title'] : $value['id']))] = $value['value'];
                }
                $context = array_merge($context, $data);
                AutomationController::dollie_trigger_handle_trigger(
                    [
                        'trigger' => $this->trigger,
                        'context' => $context,
                    ]
                );
            }
        }
    }
}

ElementorSubmitFormSpecificValue::get_instance();
