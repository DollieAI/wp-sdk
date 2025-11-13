<?php

namespace Dollie\SDK\Integrations\NinjaTables\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'ninja_tables_delete_row',
    label: 'Delete Row in Table',
    since: '1.0.0'
)]
/**
 * DeleteRowInTable.
 * php version 5.6
 *
 * @category DeleteRowInTable
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * DeleteRowInTable
 *
 * @category DeleteRowInTable
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class DeleteRowInTable extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'NinjaTables';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'ninja_tables_delete_row';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Delete Row in Table', 'dollie'),
            'action' => $this->action,
            'function' => [$this, 'action_listener'],
        ];

        return $actions;
    }

    /**
     * Action listener.
     *
     * @param int   $user_id user_id.
     * @param int   $automation_id automation_id.
     * @param array $fields fields.
     * @param array $selected_options selectedOptions.
     * @throws Exception Exception.
     *
     * @return bool|array|void
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $results = [];
        global $wpdb;

        $table_id = $selected_options['table_id'];
        $row_id = $selected_options['row_id'];
        $table_name = $wpdb->prefix . 'ninja_table_items';
        $sql = 'SELECT * FROM ' . $table_name . ' WHERE id = %d';
        $results = $wpdb->get_row($wpdb->prepare($sql, $row_id), ARRAY_A); // @phpcs:ignore
        if (empty($results)) {
            return [
                'status' => 'error',
                'message' => 'No row exist with ' . $row_id . ' ID',
            ];
        }
        $where = [
            'id' => $row_id,
        ];
        $wpdb->delete($table_name, $where);

        return true;


    }
}

DeleteRowInTable::get_instance();
