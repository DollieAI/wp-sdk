<?php

namespace Dollie\SDK\Integrations\JetpackCRM\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Integrations\JetpackCRM\JetpackCRM;
use Dollie\SDK\Traits\SingletonLoader;
use Exception;

#[Action(
    id: 'jetpack_crm_add_tag_to_company',
    label: 'Add Tag to Company',
    since: '1.0.0'
)]
/**
 * AddTagToCompany.
 * php version 5.6
 *
 * @category AddTagToCompany
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
/**
 * AddTagToCompany
 *
 * @category AddTagToCompany
 * @author   BSF <username@example.com>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GPLv3
 * @link     https://www.brainstormforce.com/
 * @since    1.0.0
 */
class AddTagToCompany extends AutomateAction
{
    use SingletonLoader;

    /**
     * Integration type.
     *
     * @var string
     */
    public $integration = 'JetpackCRM';

    /**
     * Action name.
     *
     * @var string
     */
    public $action = 'jetpack_crm_add_tag_to_company';

    /**
     * Register an action.
     *
     * @param array $actions actions.
     * @return array
     */
    public function register($actions)
    {

        $actions[$this->integration][$this->action] = [
            'label' => __('Add Tag to Company', 'dollie'),
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
     * @param array $selected_options selected_options.
     *
     * @return array
     *
     * @throws Exception Exception.
     */
    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {

        if (! function_exists('zeroBS_getCompanyIDWithEmail') || ! function_exists('zeroBSCRM_getCompanyTagsByID') || ! function_exists('zeroBSCRM_site') || ! function_exists('zeroBSCRM_team') || ! defined('ZBS_TYPE_COMPANY')) {
            return [
                'status' => 'error',
                'message' => 'Seems like Jetpack CRM plugin is not installed correctly.',
            ];
        }

        $email = sanitize_email($selected_options['company_email']);
        $tag_id = $selected_options['tag_id'];

        if (! is_email($email)) {
            return [
                'status' => 'error',
                'message' => 'Invalid email.',
            ];
        }

        $company_id = zeroBS_getCompanyIDWithEmail($email);

        if (! $company_id) {
            return [
                'status' => 'error',
                'message' => 'Company not found with this email.',
            ];
        }

        $company_tags = zeroBSCRM_getCompanyTagsByID($company_id);
        $filtered_tags = array_filter(
            $company_tags,
            function ($tag) use ($tag_id) {
                return $tag['id'] == $tag_id;
            }
        );
        $filtered_tag = reset($filtered_tags);

        if (! $filtered_tag) {
            global $wpdb;
            $wpdb->insert(
                "{$wpdb->prefix}zbs_tags_links",
                [
                    'zbs_site' => zeroBSCRM_site(),
                    'zbs_team' => zeroBSCRM_team(),
                    'zbs_owner' => 0,
                    'zbstl_objtype' => ZBS_TYPE_COMPANY,
                    'zbstl_objid' => $company_id,
                    'zbstl_tagid' => $tag_id,
                ],
                ['%d', '%d', '%d', '%d', '%d', '%d']
            );

            $company_tags = zeroBSCRM_getCompanyTagsByID($company_id);
            $filtered_tags = array_filter(
                $company_tags,
                function ($tag) use ($tag_id) {
                    return $tag['id'] == $tag_id;
                }
            );

            $filtered_tag = reset($filtered_tags);
        }

        $context = [];
        $context['tag_id'] = $filtered_tag['id'];
        $context['tag_name'] = $filtered_tag['name'];
        $context['tag_slug'] = $filtered_tag['slug'];

        return array_merge($context, JetpackCRM::get_company_context($company_id));
    }
}

AddTagToCompany::get_instance();
