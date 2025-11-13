<?php

namespace Dollie\SDK\Integrations\Voxel\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'voxel_post_followed',
    label: 'Post Followed',
    since: '1.0.0'
)]
/**
 * VoxelPostFollowed.
 * php version 5.6
 *
 * @category VoxelPostFollowed
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * VoxelPostFollowed
 *
 * @category VoxelPostFollowed
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class VoxelPostFollowed
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'Voxel';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'voxel_post_followed';

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
            'label' => __('Post Followed', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'st_voxel_post_followed',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $follow_data Follow data.
     * @return void
     */
    public function trigger_listener($follow_data)
    {
        if (empty($follow_data)) {
            return;
        }

        global $wpdb;
        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}voxel_followers WHERE object_id= %d";
        $followers = $wpdb->get_var($wpdb->prepare($sql, $follow_data['ID'])); // @phpcs:ignore
        $follow_data['total_followers'] = $followers + 1;
        $context = $follow_data;
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
VoxelPostFollowed::get_instance();
