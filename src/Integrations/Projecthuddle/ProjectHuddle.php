<?php

namespace Dollie\SDK\Integrations\Projecthuddle;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'ProjectHuddle',
    name: 'Projecthuddle',
    slug: 'projecthuddle',
    since: '1.0.0'
)]
/**
 * ProjectHuddle core integrations file
 *
 * @since   1.0.0
 */
class ProjectHuddle extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'ProjectHuddle';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('SureFeedback', 'dollie');
        $this->description = __('A WordPress plugin for Website & Design feedback.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/projecthuddle.png';
        add_action('ph_website_pre_rest_update_thread_attribute', [$this, 'ph_after_comment_resolved'], 10, 3);
    }

    /**
     * On Comment resolved.
     *
     * @param string $attr Resolved.
     * @param string $value Value.
     * @param string $object Post object.
     * @return void
     */
    public function ph_after_comment_resolved($attr, $value, $object)
    {

        if ('resolved' !== $attr) {
            return;
        }

        // if it is resolved, do something!
        if ($value) {
            $comment = $object;
            do_action('suretriggers_ph_after_comment_approval', $comment);
        }
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('\Project_Huddle');
    }
}

IntegrationsController::register(ProjectHuddle::class);
