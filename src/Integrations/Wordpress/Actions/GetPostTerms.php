<?php

namespace Dollie\SDK\Integrations\Wordpress\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'get_post_terms',
    label: 'Get Post Terms',
    since: '1.0.0'
)]
/**
 * GetPostTerms.
 * php version 5.6
 *
 * @category GetPostTerms
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * GetPostTerms
 *
 * @category GetPostTerms
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class GetPostTerms extends AutomateAction
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
    public $action = 'get_post_terms';

    /**
     * Register action.
     *
     * @param array $actions action data.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Get Post Terms', 'dollie'),
            'action' => 'get_post_terms',
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
        $taxonomy_name = $selected_options['taxonomy'];
        $terms = wp_get_post_terms($post_id, $taxonomy_name);
        if (! $terms) {
            return [
                'status' => 'error',
                'message' => 'No taxonomy term found for the post.',
            ];
        }

        return [
            $terms,
        ];
    }
}

GetPostTerms::get_instance();
