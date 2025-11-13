<?php

namespace Dollie\SDK\Integrations\GravityKit\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use GFCommon;
use GFFormsModel;

#[Trigger(
    id: 'gk_form_entry_rejected',
    label: 'Form Entry Rejected',
    since: '1.0.0'
)]
/**
 * FormEntryRejected.
 * php version 5.6
 *
 * @category FormEntryRejected
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * FormEntryRejected
 *
 * @category FormEntryRejected
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class FormEntryRejected
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'GravityKit';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'gk_form_entry_rejected';

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
            'label' => __('Form Entry Rejected', 'dollie'),
            'action' => 'gk_form_entry_rejected',
            'common_action' => 'gravityview/approve_entries/disapproved',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $args Trigger Notifications.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($args)
    {

        if (! class_exists('GFFormsModel')) {
            return;
        }

        if (! class_exists('GFCommon')) {
            return;
        }

        global $wpdb;

        $entry_id = $args;

        $user_id = ap_get_current_user_id();

        $form_id = $wpdb->get_var($wpdb->prepare("SELECT form_id from {$wpdb->prefix}gf_entry WHERE id=%d", $entry_id));

        $form = GFFormsModel::get_form_meta($form_id);
        $fields = [];

        if (is_array($form['fields'])) {
            foreach ($form['fields'] as $field) {
                if (isset($field['inputs']) && is_array($field['inputs'])) {

                    foreach ($field['inputs'] as $input) {
                        $fields[] = [$input['id'], GFCommon::get_label($field, $input['id'])];
                    }
                } elseif (! rgar($field, 'displayOnly')) {
                    $fields[] = [$field['id'], GFCommon::get_label($field)];
                }
            }
        }
        $data = [];
        foreach ($fields as $field) {
            $form_entry = $wpdb->get_var($wpdb->prepare("SELECT meta_value from {$wpdb->prefix}gf_entry_meta WHERE entry_id=%d AND meta_key=%s", $entry_id, $field[0]));
            $data[$field[1]] = $form_entry;
        }

        $context['gravity_form'] = (int) $form_id;
        $context['gravity_form_entry_id'] = (int) $entry_id;
        $context['gravity_form_data'] = $data;

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'user_id' => $user_id,
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
FormEntryRejected::get_instance();
