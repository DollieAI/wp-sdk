<?php

namespace Dollie\SDK\Integrations\Clickwhale\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'cw_link_clicked',
    label: 'Link Clicked',
    since: '1.0.0'
)]
/**
 * LinkClicked.
 * php version 5.6
 *
 * @category LinkClicked
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * LinkClicked
 *
 * @category LinkClicked
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class LinkClicked
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ClickWhale';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'cw_link_clicked';

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
            'label' => __('Link Clicked', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'clickwhale/link_clicked',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 3,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $link Link data.
     * @param int   $link_id Link ID.
     * @param int   $user_id User ID.
     * @return void
     */
    public function trigger_listener($link, $link_id, $user_id)
    {

        if (empty($link) || empty($link_id)) {
            return;
        }

        global $wpdb;
        $link_author = $wpdb->get_row($wpdb->prepare("SELECT author FROM {$wpdb->prefix}clickwhale_links WHERE id = %d", intval($link_id)));

        if ($link_author && $link_author->author) {
            $link['author_id'] = $link_author->author;


            $link['author'] = WordPress::get_user_context($link_author->author);

        }

        $context = [
            'link' => $link,
        ];

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
LinkClicked::get_instance();
