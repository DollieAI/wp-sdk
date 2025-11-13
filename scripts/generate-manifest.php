<?php

declare(strict_types=1);

/**
 * Manifest Generation Script
 *
 * This script scans the SDK source code for Integration, Trigger, and Action attributes
 * and generates a deterministic JSON manifest file.
 *
 * Usage:
 *   php scripts/generate-manifest.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Stub WordPress functions for manifest generation
if (!function_exists('add_filter')) {
    function add_filter($hook, $callback, $priority = 10, $accepted_args = 1)
    {
        return true;
    }
}
if (!function_exists('add_action')) {
    function add_action($hook, $callback, $priority = 10, $accepted_args = 1)
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
if (!function_exists('get_userdata')) {
    function get_userdata($user_id)
    {
        return false;
    }
}
if (!function_exists('apply_filters')) {
    function apply_filters($hook, $value, ...$args)
    {
        return $value;
    }
}
if (!function_exists('do_action')) {
    function do_action($hook, ...$args)
    {
    }
}

if (!defined('DOLLIE_SDK_URL')) {
    define('DOLLIE_SDK_URL', 'https://example.com/');
}
if (!defined('DOLLIE_ASSETS_URL')) {
    define('DOLLIE_ASSETS_URL', 'https://example.com/assets/');
}

use Dollie\SDK\Build\Manifest\Generator;

try {
    // Get base directory
    $baseDir = dirname(__DIR__);

    // Get version from composer.json
    $composerFile = $baseDir . '/composer.json';
    if (!file_exists($composerFile)) {
        throw new RuntimeException("composer.json not found at {$composerFile}");
    }

    $composerData = json_decode(file_get_contents($composerFile), true);
    $version = $composerData['version'] ?? '1.0.0';

    echo "===========================================\n";
    echo "Dollie SDK - Manifest Generator\n";
    echo "===========================================\n";
    echo "Version: {$version}\n";
    echo "Base Directory: {$baseDir}\n";
    echo "===========================================\n\n";

    // Create generator
    $generator = new Generator($baseDir, $version);

    // Generate manifest
    $manifest = $generator->generate();

    // Write to dist directory
    $outputDir = $baseDir . '/dist';
    $generator->write($manifest, $outputDir);

    echo "\n===========================================\n";
    echo "SUCCESS!\n";
    echo "===========================================\n";
    echo 'Generated ' . count($manifest['integrations']) . " integrations\n";
    echo "Checksum: {$manifest['checksum']}\n";
    echo "Output: {$outputDir}/manifest.json\n";
    echo "===========================================\n";

    exit(0);
} catch (Throwable $e) {
    echo "\n===========================================\n";
    echo "ERROR!\n";
    echo "===========================================\n";
    echo $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
    echo "===========================================\n";

    exit(1);
}
