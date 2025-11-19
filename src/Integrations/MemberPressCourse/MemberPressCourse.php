<?php

namespace Dollie\SDK\Integrations\MemberPressCourse;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;
use memberpress\courses\models as models;

#[Integration(
    id: 'MemberPressCourse',
    name: 'MemberpressCourse',
    slug: 'memberpress-course',
    since: '1.0.0'
)]
/**
 * MemberPress course integrations file
 *
 * @since 1.0.0
 */
class MemberPressCourse extends BaseIntegration
{
    use SingletonLoader;

    /**
     * ID
     *
     * @var string
     */
    protected $id = 'MemberPressCourse';

    /**
     * SureTrigger constructor.
     */
    public function __construct()
    {
        $this->name = __('MemberPressCourse', 'dollie');
        $this->description = __('Easily Create And Sell Online Courses On Your WP Site With MemberPressCourse.', 'dollie');
        $this->icon_url = DOLLIE_SDK_URL . 'assets/icons/memberpresscourse.png';

    }

    /**
     * Is Plugin depended on plugin is installed or not.
     *
     * @return bool
     */
    public function is_plugin_installed(): bool
    {
        if (in_array('memberpress-courses/main.php', apply_filters('active_plugins', get_option('active_plugins'))) && class_exists('MeprCtrlFactory')) {
            return true;
        } else {

            // Plugin is not active or installed.
            return false;
        }
    }

    /**
     * Find lessons.
     *
     * @param int $section_id section id.
     *
     * @return mixed
     */
    public static function find_all_by_section($section_id)
    {
        if (! class_exists('\memberpress\courses\models\Lesson')) {
            return;
        }
        global $wpdb;
        $post_types_string = models\Lesson::lesson_cpts();
        $post_types_string = implode("','", $post_types_string);

        $query = $wpdb->prepare(
            "SELECT ID, post_type FROM {$wpdb->posts} AS p
	        JOIN {$wpdb->postmeta} AS pm
	          ON p.ID = pm.post_id
	         AND pm.meta_key = %s
	         AND pm.meta_value = %s
	        JOIN {$wpdb->postmeta} AS pm_order
	          ON p.ID = pm_order.post_id
	         AND pm_order.meta_key = %s
	       WHERE p.post_type in ( %s ) AND p.post_status <> 'trash'
	       ORDER BY pm_order.meta_value * 1",
            models\Lesson::$section_id_str,
            $section_id,
            models\Lesson::$lesson_order_str,
            stripcslashes($post_types_string)
        );

        $db_lessons = $wpdb->get_results(stripcslashes($query)); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
        $lessons = [];

        foreach ($db_lessons as $lesson) {
            if (! class_exists('\memberpress\courses\models\Quiz')) {
                return;
            }
            if (models\Quiz::$cpt === $lesson->post_type) {
                $lessons[] = $lesson->ID;
            } else {
                $lessons[] = $lesson->ID;
            }
        }

        return $lessons;
    }
}

IntegrationsController::register(MemberPressCourse::class);
