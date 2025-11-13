<?php

namespace Dollie\SDK\Integrations\Buddyboss;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'BuddyBoss',
    name: 'Buddyboss',
    slug: 'buddyboss',
    since: '1.0.0'
)]
/**
 * BuddyBoss integration file
 *
 * @since 1.0.0
 */
/**
 * Class BuddyBoss
 */
class BuddyBoss extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID of the integration
     *
     * @var string
     */
    protected $id = 'BuddyBoss';

    /**
     * BuddyBoss constructor.
     */
    public function __construct()
    {
        $this->name = __('BuddyBoss', 'dollie');
    }

    /**
     * Check if content has links.
     *
     * @param string $content content.
     * @return array|string
     */
    public static function st_content_has_links($content)
    {
        // Define a regular expression pattern to match URLs.
        $pattern = '/<a\b[^>]*href=["\']([^"\'#]+)/i';

        // Use preg_match_all to find all links in the content.
        preg_match_all($pattern, $content, $matches);

        // Return the array of matched links.
        return $matches[1];
    }

    /**
     * Check plugin is installed.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        if (function_exists('buddypress') && isset(buddypress()->buddyboss) && buddypress()->buddyboss) {
            return true;
        } else {
            return false;
        }
    }
}

IntegrationsController::register(BuddyBoss::class);
