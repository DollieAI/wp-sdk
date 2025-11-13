<?php

namespace Dollie\SDK\Integrations\WPFusion\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wf_tag_added_to_user',
    label: 'Tag Added To User',
    since: '1.0.0'
)]
/**
 * WfTagAddedToUser.
 * php version 5.6
 *
 * @category WfTagAddedToUser
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * WfTagAddedToUser
 *
 * @category WfTagAddedToUser
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class WfTagAddedToUser
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPFusion';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wf_tag_added_to_user';

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
     *
     * @return array
     */
    public function register($triggers)
    {

        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Tag Added To User', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wpf_tags_applied',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int   $user_id User ID.
     * @param array $tags Tags.
     *
     * @return void
     */
    public function trigger_listener($user_id, $tags)
    {

        if (! function_exists('wp_fusion')) {
            return;
        }

        $context['user_id'] = WordPress::get_user_context($user_id);
        foreach ($tags as $tag) {
            $context['fusion_tag'] = $tag;
        }
        $context['tags'] = wp_fusion()->user->get_tags($user_id, true);

        AutomationController::dollie_trigger_handle_trigger(
            [
                'trigger' => $this->trigger,
                'wp_user_id' => $user_id,
                'context' => $context,
            ]
        );
    }
}

WfTagAddedToUser::get_instance();
