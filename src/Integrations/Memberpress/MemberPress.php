<?php

namespace Dollie\SDK\Integrations\Memberpress;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'MemberPress',
    name: 'Memberpress',
    slug: 'memberpress',
    since: '1.0.0'
)]
/**
 * MemberPress core integrations file
 *
 * @since 1.0.0
 */
class MemberPress extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'MemberPress';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('MemberPress', 'dollie');
        $this->description = __('MemberPress will Help Build Membership Site.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/memberpress.svg';

    }

    /**
     * Fetch membership context.
     *
     * @param object $subscription subscription object.
     * @return array
     */
    public static function get_membership_context($subscription)
    {
        $context = [];
        if (! class_exists('MeprTransaction') || ! $subscription instanceof \MeprTransaction) {
            return $context;
        }
        $context['membership_id'] = $subscription->product_id;
        $context['membership_title'] = get_the_title($subscription->product_id);
        $context['amount'] = $subscription->amount;
        $context['total'] = $subscription->total;
        $context['tax_amount'] = $subscription->tax_amount;
        $context['tax_rate'] = $subscription->tax_rate;
        $context['trans_num'] = $subscription->trans_num;
        $context['status'] = $subscription->status;
        $context['subscription_id'] = $subscription->subscription_id;
        $context['membership_url'] = get_permalink($subscription->product_id);
        $context['membership_featured_image_id'] = get_post_meta($subscription->product_id, '_thumbnail_id', true);
        $context['membership_featured_image_url'] = get_the_post_thumbnail_url($subscription->product_id);

        return $context;
    }

    /**
     * Fetch membership context.
     *
     * @param object $subscription subscription object.
     * @return array
     */
    public static function get_subscription_context($subscription)
    {
        $context = [];
        if (! class_exists('MeprSubscription') || ! $subscription instanceof \MeprSubscription) {
            return $context;
        }
        $context['membership_id'] = $subscription->product_id;
        $context['membership_title'] = get_the_title($subscription->product_id);
        $context['user_id'] = $subscription->user_id;
        $context['amount'] = $subscription->price;
        $context['total'] = $subscription->total;
        $context['tax_amount'] = $subscription->tax_amount;
        $context['tax_rate'] = $subscription->tax_rate;
        $context['status'] = $subscription->status;
        $context['subscription_id'] = $subscription->id;

        $context['membership_url'] = get_permalink($subscription->product_id);
        $context['membership_featured_image_id'] = get_post_meta($subscription->product_id, '_thumbnail_id', true);
        $context['membership_featured_image_url'] = get_the_post_thumbnail_url($subscription->product_id);

        return $context;
    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('MeprCtrlFactory');
    }
}

IntegrationsController::register(MemberPress::class);
