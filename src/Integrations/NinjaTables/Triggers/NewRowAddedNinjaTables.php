<?php

namespace Dollie\SDK\Integrations\NinjaTables\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'ninja_tables_new_row_added',
    label: 'New Row Added',
    since: '1.0.0'
)]
/**
 * NewRowAddedNinjaTables.
 * php version 5.6
 *
 * @category NewRowAddedNinjaTables
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * NewRowAddedNinjaTables
 *
 * @category NewRowAddedNinjaTables
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class NewRowAddedNinjaTables
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'NinjaTables';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'ninja_tables_new_row_added';

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
            'label' => __('New Row Added', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'ninja_table_after_add_item',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $insert_id insert Id.
     * @param int $table_id table Id.
     * @param array   $attributes attributes.
     *
     * @return void
     */
    public function trigger_listener($insert_id, $table_id, $attributes)
    {
        global $wpdb;
        if (empty($insert_id)) {
            return;
        }
        $results = [];
        $sql = 'SELECT * FROM ' . $wpdb->prefix . 'ninja_table_items WHERE table_id = %d AND id = %d ORDER BY id DESC LIMIT 1';
        $results = $wpdb->get_row($wpdb->prepare($sql, $table_id, $insert_id), ARRAY_A); // @phpcs:ignore
        if (! empty($results)) {
            $results['value'] = json_decode($results['value'], true);
            $results['owner'] = WordPress::get_user_context($results['owner_id']);
        }

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'context' => $results,
            ]
        );
    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
NewRowAddedNinjaTables::get_instance();
