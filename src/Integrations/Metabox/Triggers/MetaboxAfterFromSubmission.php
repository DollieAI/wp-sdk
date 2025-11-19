<?php

namespace Dollie\SDK\Integrations\Metabox\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Integrations\WordPress\WordPress;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'metabox_after_form_submission',
    label: 'User Metabox Field Updated',
    since: '1.0.0'
)]
/**
 * MetaboxAfterFromSubmission.
 * php version 5.6
 *
 * @category MetaboxAfterFromSubmission
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * MetaboxAfterFromSubmission
 *
 * @category MetaboxAfterFromSubmission
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class MetaboxAfterFromSubmission
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'MetaBox';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'metabox_after_form_submission';

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
            'label' => __('User Metabox Field Updated', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'rwmb_frontend_after_process',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array $config Form config.
     * @param int   $post_id Post ID.
     * @return void|bool
     */
    public function trigger_listener($config, $post_id)
    {

        $response_array = [
            'post_id' => $post_id,
        ];
        $field_values = $config;

        foreach ($field_values as $id => $value) {
            $response_array['form_' . $id] = is_array($value) ? wp_json_encode($value) : $value;
        }
        $context = $response_array;
        $context['post'] = WordPress::get_post_context($post_id);

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
MetaboxAfterFromSubmission::get_instance();
