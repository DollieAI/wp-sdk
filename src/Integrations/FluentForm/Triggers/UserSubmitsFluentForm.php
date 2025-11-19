<?php

namespace Dollie\SDK\Integrations\FluentForm\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'user_submits_fluentform',
    label: 'User Submits Form',
    since: '1.0.0'
)]
/**
 * UserSubmitsFluentForm.
 * php version 5.6
 *
 * @category UserSubmitsFluentForm
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * UserSubmitsFluentForm
 *
 * @category UserSubmitsFluentForm
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class UserSubmitsFluentForm
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentForm';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'user_submits_fluentform';

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
            'action' => 'user_submits_fluentform',
            'common_action' => 'fluentform/before_insert_submission',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array  $insert_data submission_data Array.
     * @param array  $data $_POST[‘data’] from submission.
     * @param object $form The $form Object.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($insert_data, $data, $form)
    {
        if (empty($form)) {
            return;
        }

        $context = (array) json_decode($insert_data['response'], true);
        if (isset($form->id)) {
            $context['form_id'] = (int) $form->id;
        }
        if (isset($form->title)) {
            $context['form_title'] = $form->title;
        }
        $context['entry_id'] = $insert_data['serial_number'];
        $context['entry_source_url'] = $insert_data['source_url'];
        $context['submission_date'] = $insert_data['created_at'];
        $context['user_ip'] = $insert_data['ip'];

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
UserSubmitsFluentForm::get_instance();
