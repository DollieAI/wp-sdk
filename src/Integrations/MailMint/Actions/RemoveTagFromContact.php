<?php

namespace Dollie\SDK\Integrations\MailMint\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use Mint\MRM\DataBase\Models\ContactGroupModel;
use Mint\MRM\DataBase\Models\ContactGroupPivotModel;
use Mint\MRM\DataBase\Models\ContactModel;

#[Action(
    id: 'mail_mint_remove_tags_from_contact',
    label: 'Remove Tags From Contact',
    since: '1.0.0'
)]
/**
 * RemoveTagFromContact.
 * php version 5.6
 *
 * @category RemoveTagFromContact
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * RemoveTagFromContact
 *
 * @category RemoveTagFromContact
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class RemoveTagFromContact extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'MailMint';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'mail_mint_remove_tags_from_contact';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Remove Tags From Contact', 'dollie'),
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
     *
     * @return array|void
     * @throws Exception Error.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        if (! class_exists('Mint\MRM\DataBase\Models\ContactGroupModel') || ! class_exists('Mint\MRM\DataBase\Models\ContactModel') || ! class_exists('Mint\MRM\DataBase\Models\ContactGroupPivotModel')) {
            return;
        }
        $contact_id = $selected_options['contact_id'] ? $selected_options['contact_id'] : 0;
        if (! ContactModel::is_contact_ids_exists([$contact_id])) {
            return [
                'status' => 'error',
                'message' => 'There is no contact with provided id.',
            ];
        }
        $selected_tags = $selected_options['contact_tags'] ? explode(',', $selected_options['contact_tags']) : [];
        $tags_data = [];
        foreach ($selected_tags as $tag) {
            $tags = ContactGroupModel::is_group_exists($tag, 'tags');
            if ($tags) {
                $tags_data[] = ['id' => $tags];
            }
        }

        ContactGroupPivotModel::remove_groups_from_contacts($tags_data, [$contact_id]);


        return ContactModel::get($contact_id);

    }
}

RemoveTagFromContact::get_instance();
