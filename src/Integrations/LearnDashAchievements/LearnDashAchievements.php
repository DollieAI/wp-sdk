<?php

namespace Dollie\SDK\Integrations\LearnDashAchievements;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'LearnDashAchievements',
    name: 'LearndashAchievements',
    slug: 'learndash-achievements',
    since: '1.0.0'
)]
/**
 * LearnDashAchievements core integrations file
 *
 * @since 1.0.0
 */
class LearnDashAchievements extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'LearnDashAchievements';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('LearnDash Achievements', 'dollie');
        $this->description = __('The most powerful learning management system for WordPress. LearnDash Achievements empowers you to recognize and celebrate your learners` accomplishments with customizable rewards and achievements.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/learnDash-achievements.svg';

    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists('SFWD_LMS') && class_exists('\LearnDash_Achievements');
    }
}

IntegrationsController::register(LearnDashAchievements::class);
