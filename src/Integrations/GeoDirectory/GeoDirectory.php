<?php

namespace Dollie\SDK\Integrations\GeoDirectory;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use WP_Term;

#[Integration(
    id: 'GeoDirectory',
    name: 'GeoDirectory',
    slug: 'geo-directory',
    since: '1.0.0'
)]
/**
 * GeoDirectory core integrations file
 *
 * @since 1.0.0
 */
/**
 * Class GeoDirectory
 */
class GeoDirectory extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'GeoDirectory';

    /**
     * Get term details
     *
     * @param array  $gd_tags gd tags.
     * @param string $taxonomy taxonomy.
     * @return array
     */
    public static function get_place_terms($gd_tags, $taxonomy)
    {
        $terms = [];
        foreach ($gd_tags as $tag) {
            $term = get_term_by('name', $tag, $taxonomy);
            if ($term instanceof WP_Term) {
                $term_id = $term->term_id;
            } else {
                $term = get_term_by('slug', $tag, $taxonomy);
                if ($term instanceof WP_Term) {
                    $term_id = $term->term_id;
                } else {
                    $term = get_term_by('id', $tag, $taxonomy);
                    if ($term instanceof WP_Term) {
                        $term_id = $term->term_id;
                    } else {
                        // If term is not found, set term_id to null or handle appropriately.
                        $term_id = null;
                    }
                }
            }
            // Only push term_id if it's not null.
            if (null !== $term_id) {
                $terms[] = $term_id;
            }
        }

        return $terms;
    }

    /**
     * Is Plugin depended plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        return class_exists(self::class);
    }
}

IntegrationsController::register(GeoDirectory::class);
