<?php

namespace Dollie\SDK\Integrations\Wordpress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'get_post_taxonomy',
    label: 'Get Post Taxonomies',
    since: '1.0.0'
)]
/**
 * GetPostTaxonomy.
 * php version 5.6
 *
 * @category GetPostTaxonomy
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GetPostTaxonomy
 *
 * @category GetPostTaxonomy
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetPostTaxonomy extends AutomateAction
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
    public $action = 'get_post_taxonomy';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get Post Taxonomies', 'dollie'),
            'action' => 'get_post_taxonomy',
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
        $post_id = $selected_options['post_id'];

        $taxonomies = get_post_taxonomies($post_id);
        if (! $taxonomies) {
            return [
                'status' => 'error',
                'message' => 'No taxonomies found for the post.',
            ];
        }

        return $taxonomies;
    }
}

GetPostTaxonomy::get_instance();
