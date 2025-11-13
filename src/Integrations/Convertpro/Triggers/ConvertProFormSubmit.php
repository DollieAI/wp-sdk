<?php

namespace Dollie\SDK\Integrations\Convertpro\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'convert_pro_form_submit',
    label: 'Form Submitted',
    since: '1.0.0'
)]
/**
 * ConvertProFormSubmit.
 * php version 5.6
 *
 * @category ConvertProFormSubmit
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ConvertProFormSubmit
 *
 * @category ConvertProFormSubmit
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class ConvertProFormSubmit
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'ConvertPro';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'convert_pro_form_submit';

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
            'label' => __('Form Submitted', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'cpro_form_submit',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;

    }

    /**
     * Trigger listener
     *
     * @param array $response Response Data.
     * @param array $post_data Post Data.
     * @since 1.0.0
     *
     * @return void
     */
    public function trigger_listener($response, $post_data)
    {
        if (empty($response)) {
            return;
        }

        $style_id = isset($post_data['style_id']) ? (int) sanitize_text_field(esc_attr($post_data['style_id'])) : '';

        if (is_array($post_data['param']) && count($post_data['param'])) {
            foreach ($post_data['param'] as $key => $value) {
                $context[ucfirst($key)] = $value;
            }
        }
        $context['convertpro_form'] = (int) $style_id;

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
ConvertProFormSubmit::get_instance();
