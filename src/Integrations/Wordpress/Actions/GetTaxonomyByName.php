<?php

namespace Dollie\SDK\Integrations\Wordpress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'get_taxonomy_by_name',
    label: 'Get Taxonomy By Name',
    since: '1.0.0'
)]
/**
 * GetTaxonomyByName.
 * php version 5.6
 *
 * @category GetTaxonomyByName
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GetTaxonomyByName
 *
 * @category GetTaxonomyByName
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetTaxonomyByName extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WordPress';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'get_taxonomy_by_name';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get Taxonomy By Name', 'dollie'),
            'action' => 'get_taxonomy_by_name',
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
     * @return array|bool
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $taxonomy_name = $selected_options['taxonomy_name'];
        $taxonomy_term = $selected_options['taxonomy_term'];

        $data = get_term_by('name', $taxonomy_term, $taxonomy_name);

        if (! $data) {
            return [
                'status' => 'error',
                'message' => 'No taxonomy is found.',
            ];
        }

        return [
            $data,
        ];
    }
}

GetTaxonomyByName::get_instance();
