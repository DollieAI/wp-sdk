<?php

namespace Dollie\SDK\Integrations\MailMint\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;
use Mint\MRM\DataBase\Models\CampaignModel;

#[Action(
    id: 'mail_mint_get_all_campaigns',
    label: 'List Campaigns',
    since: '1.0.0'
)]
/**
 * ListCampaigns.
 * php version 5.6
 *
 * @category ListCampaigns
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * ListCampaigns
 *
 * @category ListCampaigns
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class ListCampaigns extends AutomateAction
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
    public $action = 'mail_mint_get_all_campaigns';

    /**
     * Register a action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('List Campaigns', 'dollie'),
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
        if (! class_exists('Mint\MRM\DataBase\Models\CampaignModel')) {
            return [
                'status' => 'error',
                'message' => __('Mint\MRM\DataBase\Models\CampaignModel class not found.', 'dollie'),

            ];
        }
        global $wpdb;
        $limit = 20;
        if (isset($selected_options['limit']) && ! empty($selected_options['limit'])) {
            $limit = $selected_options['limit'];
        }
        $campaigns = CampaignModel::get_all($wpdb, 0, $limit);
        if (! empty($campaigns)) {
            return $campaigns['campaigns'];
        } else {
            return [];
        }

    }
}

ListCampaigns::get_instance();
