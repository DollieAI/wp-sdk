<?php

namespace Dollie\SDK\Integrations\WPTravelEngine\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'wte_enquiry_submitted',
    label: 'New Enquiry Submitted',
    since: '1.0.0'
)]
/**
 * NewEnquirySubmitted.
 * php version 5.6
 *
 * @category NewEnquirySubmitted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * NewEnquirySubmitted
 *
 * @category NewEnquirySubmitted
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class NewEnquirySubmitted
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'WPTravelEngine';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'wte_enquiry_submitted';

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
            'label' => __('New Enquiry Submitted', 'dollie'),
            'action' => $this->trigger,
            'common_action' => 'wte_after_enquiry_created',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 1,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param int $post_id Enquiry post ID.
     * @return void
     */
    public function trigger_listener($post_id)
    {
        if (empty($post_id)) {
            return;
        }
        $data = get_post($post_id);

        $context = [
            'enquiry_post_id' => $post_id,
            'post_data' => $data,
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
NewEnquirySubmitted::get_instance();
