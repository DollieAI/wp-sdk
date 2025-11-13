<?php

namespace Dollie\SDK\Integrations\Fluentcrm;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use FluentCrm\App\Models\Lists;
use FluentCrm\App\Models\Tag;

#[Integration(
    id: 'FluentCRM',
    name: 'Fluentcrm',
    slug: 'fluentcrm',
    since: '1.0.0'
)]
/**
 * FluentCRM core integrations file
 *
 * @since 1.0.0
 */
class FluentCRM extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'FluentCRM';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('FluentCRM', 'dollie');
        $this->description = __('FluentCRM is a Self Hosted Email Marketing Automation Plugin for WordPress.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/fluentCRM.svg';

    }

    /**
     * Fetch tag data.
     *
     * @param int $tag_id tag id.
     * @return mixed|array
     */
    public function get_tag_data($tag_id)
    {
        if (! class_exists('FluentCrm\App\Models\Tag')) {
            return [];
        }
        $tag = Tag::where('id', $tag_id)->get();

        return $tag;
    }

    /**
     * Fetch list data.
     *
     * @param int $list_id list data.
     * @return mixed
     */
    public function get_list_data($list_id)
    {
        if (! class_exists('FluentCrm\App\Models\Lists')) {
            return [];
        }
        $list = Lists::find($list_id);

        return $list;
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return defined('FLUENTCRM');
    }
}

IntegrationsController::register(FluentCRM::class);
