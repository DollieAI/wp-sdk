<?php

namespace Dollie\SDK\Integrations\ThriveLeads\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'tl_form_submitted',
    label: 'Form Submitted',
    since: '1.0.0'
)]
/**
 * FormSubmitted.
 * php version 5.6
 *
 * @category FormSubmitted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * FormSubmitted
 *
 * @category FormSubmitted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class FormSubmitted
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
    public $trigger = 'tl_form_submitted';

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
            'action' => 'tl_form_submitted',
            'common_action' => 'tcb_api_form_submit',
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
        if (! empty($data)) {
            if (array_key_exists('thrive_leads', $data)) {
                $data['post_id'] = $data['thrive_leads']['tl_data']['form_type_id'];
            }
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
FormSubmitted::get_instance();
