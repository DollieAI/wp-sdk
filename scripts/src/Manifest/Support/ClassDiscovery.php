<?php

declare(strict_types=1);

namespace Dollie\SDK\Build\Manifest\Support;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Class Discovery Utility
 *
 * Discovers PHP classes in specified directories and namespaces.
 */
class ClassDiscovery
{
    /**
     * Discover all classes in a directory matching a namespace prefix
     *
     * @param string $directory Base directory to scan
     * @param string $namespacePrefix Namespace prefix to match (e.g., 'Dollie\SDK\Integrations')
     * @return array<string> Array of fully qualified class names
     */
    public static function discover(string $directory, string $namespacePrefix): array
    {
        if (!is_dir($directory)) {
            throw new \RuntimeException("Directory not found: {$directory}");
        }

        $classes = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        $phpFiles = new RegexIterator($iterator, '/^.+\.php$/i');

        foreach ($phpFiles as $file) {
            $className = self::extractClassName($file->getPathname(), $directory, $namespacePrefix);
            if ($className !== null) {
                $classes[] = $className;
            }
        }

        return $classes;
    }

    /**
     * Extract the fully qualified class name from a file path
     *
     * @param string $filePath Path to the PHP file
     * @param string $baseDirectory Base directory to remove from path
     * @param string $namespacePrefix Namespace prefix to prepend
     * @return string|null Fully qualified class name or null if not found
     */
    private static function extractClassName(string $filePath, string $baseDirectory, string $namespacePrefix): ?string
    {
        // Get relative path from base directory
        $relativePath = str_replace($baseDirectory, '', $filePath);
        $relativePath = ltrim($relativePath, '/\\');

        // Remove .php extension
        $classPath = substr($relativePath, 0, -4);

        // Convert path separators to namespace separators
        $classPath = str_replace(['/', '\\'], '\\', $classPath);

        // Construct fully qualified class name
        $className = rtrim($namespacePrefix, '\\') . '\\' . $classPath;

        // Verify the class exists by checking if file contains class declaration
        $content = file_get_contents($filePath);
        if ($content === false) {
            return null;
        }

        // Extract the actual class name from the file
        if (preg_match('/\bclass\s+(\w+)/i', $content, $matches)) {
            $actualClassName = $matches[1];

            // Verify it matches our expected class name
            if (str_ends_with($className, '\\' . $actualClassName)) {
                return $className;
            }
        }

        return null;
    }

    /**
     * Load classes and ensure they're available via autoloader
     *
     * @param array<string> $classNames Array of fully qualified class names
     * @return void
     */
    public static function loadClasses(array $classNames): void
    {
        foreach ($classNames as $className) {
            if (class_exists($className)) {
                // Class already loaded or autoloadable
                continue;
            }
        }
    }
}
