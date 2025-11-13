<?php

namespace Dollie\SDK\Integrations\Fluentcrm\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'fluentcrm_create_tag',
    label: 'Create Tag',
    since: '1.0.0'
)]
/**
 * CreateTag.
 * php version 5.6
 *
 * @category CreateTag
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * CreateTag
 *
 * @category CreateTag
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class CreateTag extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'FluentCRM';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'fluentcrm_create_tag';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Create Tag', 'dollie'),
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
     * @return array
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! class_exists('\FluentCrm\App\Models\Tag')) {
            return [];
        }
        $tag_name = $selected_options['tag_name'];
        $tag = \FluentCrm\App\Models\Tag::updateOrCreate(
            [
                'title' => sanitize_text_field($tag_name),
            ]
        );
        if ($tag->wasRecentlyCreated) { // @phpcs:ignore
            do_action('fluentcrm_tag_created', $tag->id);
            do_action('fluent_crm/tag_created', $tag); // @phpcs:ignore
        } else {
            do_action('fluentcrm_tag_updated', $tag->id);
            do_action('fluent_crm/tag_updated', $tag); // @phpcs:ignore
        }

        return $tag;
    }
}

CreateTag::get_instance();
