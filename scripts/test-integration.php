<?php

declare(strict_types=1);

/**
 * Test Integration Loading
 *
 * Tests if integrations can be loaded without fatal errors
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dollie\SDK\Controllers\IntegrationsController;

// Simulate WordPress functions for testing only
if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        return true;
    }
}
if (!function_exists('__')) {
    function __($text, $domain = 'default')
    {
        return $text;
    }
}

echo "===========================================\n";
echo "Testing Integration Loading\n";
echo "===========================================\n\n";

// Test WordPress integration
echo "Testing WordPress integration...\n";

try {
    require_once __DIR__ . '/../src/Integrations/Wordpress/Wordpress.php';

    $wordpress = \Dollie\SDK\Integrations\Wordpress\WordPress::get_instance();

    echo "✓ WordPress integration instantiated successfully\n";
    echo '  ID: ' . $wordpress->get_id() . "\n";

    // Check if it's registered in controller
    if (IntegrationsController::is_registered('WordPress')) {
        echo "✓ WordPress integration registered in controller\n";
    } else {
        echo "✗ WordPress integration NOT registered in controller\n";
    }

} catch (\Throwable $e) {
    echo "✗ Error loading WordPress integration:\n";
    echo '  ' . $e->getMessage() . "\n";
    echo '  in ' . $e->getFile() . ':' . $e->getLine() . "\n";
}

echo "\n";

// Test WordPress trigger
echo "Testing WordPress UserCreate trigger...\n";

try {
    require_once __DIR__ . '/../src/Integrations/Wordpress/Triggers/UserCreate.php';

    $userCreate = \Dollie\SDK\Integrations\Wordpress\Triggers\UserCreate::get_instance();

    echo "✓ UserCreate trigger instantiated successfully\n";
    echo '  Integration: ' . $userCreate->integration . "\n";
    echo '  Trigger: ' . $userCreate->trigger . "\n";

} catch (\Throwable $e) {
    echo "✗ Error loading UserCreate trigger:\n";
    echo '  ' . $e->getMessage() . "\n";
    echo '  in ' . $e->getFile() . ':' . $e->getLine() . "\n";
}

echo "\n===========================================\n";
echo "Test Complete\n";
echo "===========================================\n";
