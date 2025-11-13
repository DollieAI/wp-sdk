<?php

namespace Dollie\SDK\Integrations\Wishlistmember;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'WishlistMember',
    name: 'Wishlistmember',
    slug: 'wishlistmember',
    since: '1.0.0'
)]
/**
 * WishlistMember core integrations file
 *
 * @since 1.0.0
 */
class WishlistMember extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'WishlistMember';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('WishlistMember', 'dollie');
        $this->description = __('Connect with your fans, faster your community.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/wishlistmember.svg';

    }

    /**
     * Get customer context data.
     *
     * @param array|int $level_id level.
     * @param mixed     $user_id user.
     * @return array
     */
    public static function get_membership_detail_context($level_id, $user_id)
    {
        $wm_membership_detail = wlmapi_get_level($level_id);
        if (! is_array($wm_membership_detail)) {
            return [];
        }
        $context['membership_level_id'] = isset($wm_membership_detail['level']) ? $wm_membership_detail['level']['id'] : '';
        $context['membership_level_name'] = isset($wm_membership_detail['level']) ? $wm_membership_detail['level']['name'] : '';
        $user_info = self::get_user_info($level_id, $user_id);

        return array_merge($context, $user_info);
    }

    /**
     * Get customer context data.
     *
     * @param array|int $level_id level.
     * @param mixed     $user_id user.
     * @return array
     */
    public static function get_user_info($level_id, $user_id)
    {
        $context = [];
        if (function_exists('wlmapi_get_member')) {
            $member = wlmapi_get_member($user_id);
            if (isset($member['member'])) {
                $memberinfo = $member['member'][0]['UserInfo'];
                $member_levels = $member['member'][0]['Levels'];
                $context['user_registered_date'] = $memberinfo['user_registered'];
                $context['user'] = $memberinfo['wpm_useraddress'] ? $memberinfo['wpm_useraddress'] : [];
                if (isset($member_levels[$level_id])) {
                    $context['user_registered_date_in_level'] = ($member_levels[$level_id])->Timestamp;
                }
            }
        }

        return $context;
    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('WLMAPI') || class_exists('WishListMember');
    }
}

IntegrationsController::register(WishlistMember::class);
