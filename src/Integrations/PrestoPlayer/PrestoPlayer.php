<?php

namespace Dollie\SDK\Integrations\PrestoPlayer;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'PrestoPlayer',
    name: 'PrestoPlayer',
    slug: 'presto-player',
    since: '1.0.0'
)]
/**
 * PrestoPlayer core integrations file
 *
 * @since 1.0.0
 */
class PrestoPlayer extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'PrestoPlayer';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('PrestoPlayer', 'dollie');
        $this->description = __('Connect with your fans, faster your community.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/presto-player.svg';

    }

    /**
     * Is Plugin dependent plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return function_exists('presto_player_plugin');
    }
}

IntegrationsController::register(PrestoPlayer::class);
