<?php

namespace Dollie\SDK\Integrations\MailMint\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use Mint\MRM\DataBase\Models\ContactGroupModel;
use Mint\MRM\DataBase\Models\ContactModel;

#[Trigger(
    id: 'mail_mint_tags_added_to_contact',
    label: 'TagAppliedToContact',
    since: '1.0.0'
)]
/**
 * TagAppliedToContact.
 * php version 5.6
 *
 * @category TagAppliedToContact
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * TagAppliedToContact
 *
 * @category TagAppliedToContact
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 *
 * @psalm-suppress UndefinedTrait
 */
class TagAppliedToContact
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'MailMint';

    /**
     * Trigger name.
     *
     * @var string
     */
    public $trigger = 'mail_mint_tags_added_to_contact';

    /**
     * Constructor
     *
     * @since 1.0.0
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
            'label' => 'Tags Added To Contact',
            'action' => $this->trigger,
            'common_action' => 'mailmint_tag_applied',
            'function' => [$this, 'trigger_listener'],
            'priority' => 10,
            'accepted_args' => 2,
        ];

        return $triggers;
    }

    /**
     * Trigger listener
     *
     * @param array     $tags List of tags.
     * @param int|array $contact_id Contact Id.
     * @throws Exception Error.
     * @return void
     */
    public function trigger_listener($tags, $contact_id)
    {
        if (! class_exists('Mint\MRM\DataBase\Models\ContactGroupModel') || ! class_exists('Mint\MRM\DataBase\Models\ContactModel')) {
            return;
        }
        if (! ContactModel::is_contact_ids_exists([$contact_id])) {
            throw new Exception('There is no contact with provided id.');
        }
        if (empty($tags)) {
            return;
        }

        $new_tags = array_filter(
            $tags,
            function ($tag) {
                return ! isset($tag['created_at']);
            }
        );

        foreach ($new_tags as $newtag) {
            $context['contact_id'] = $contact_id;
            $context['contact'] = ContactModel::get($contact_id);
            $context['tags'] = ContactGroupModel::get($newtag['id']);
            AutomationController::dollie_trigger_handle_trigger(
                [
                    'trigger' => $this->trigger,
                    'context' => $context,
                ]
            );
        }

    }
}

/**
 * Ignore false positive
 *
 * @psalm-suppress UndefinedMethod
 */
TagAppliedToContact::get_instance();
