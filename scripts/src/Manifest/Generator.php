<?php

declare(strict_types=1);

namespace Dollie\SDK\Build\Manifest;

use Dollie\SDK\Attributes\Action as ActionAttribute;
use Dollie\SDK\Attributes\Integration as IntegrationAttribute;
use Dollie\SDK\Attributes\Trigger as TriggerAttribute;
use Dollie\SDK\Build\Manifest\Support\ClassDiscovery;
use ReflectionClass;
use RuntimeException;

/**
 * Manifest Generator
 *
 * Generates JSON manifest files from PHP attributes.
 */
class Generator
{
    private string $baseDir;

    private string $version;

    private array $integrations = [];

    public function __construct(string $baseDir, string $version)
    {
        $this->baseDir = rtrim($baseDir, '/');
        $this->version = $version;
    }

    /**
     * Generate the complete manifest
     *
     * @return array<string, mixed>
     */
    public function generate(): array
    {
        echo "Discovering integrations...\n";
        $this->discoverIntegrations();

        echo "Building manifest...\n";
        $manifest = $this->buildManifest();

        echo "Validating manifest...\n";
        $this->validateManifest($manifest);

        return $manifest;
    }

    /**
     * Discover all integration classes
     */
    private function discoverIntegrations(): void
    {
        $integrationsDir = $this->baseDir . '/src/Integrations';

        if (!is_dir($integrationsDir)) {
            throw new RuntimeException("Integrations directory not found: {$integrationsDir}");
        }

        $classes = ClassDiscovery::discover($integrationsDir, 'Dollie\\SDK\\Integrations');

        echo 'Found ' . count($classes) . " potential classes\n";

        foreach ($classes as $className) {
            try {
                if (!class_exists($className)) {
                    continue;
                }

                $reflection = new ReflectionClass($className);
                $attributes = $reflection->getAttributes(IntegrationAttribute::class);

                if (empty($attributes)) {
                    continue;
                }

                $this->processIntegration($reflection, $attributes[0]->newInstance());
            } catch (\Throwable $e) {
                echo "Warning: Could not process class {$className}: {$e->getMessage()}\n";
            }
        }

        echo 'Processed ' . count($this->integrations) . " integrations\n";
    }

    /**
     * Process an integration class
     */
    private function processIntegration(ReflectionClass $reflection, IntegrationAttribute $attribute): void
    {
        $integrationData = $attribute->toArray();
        $integrationId = $attribute->id;

        echo "Processing integration: {$integrationId}\n";

        // Find triggers and actions in the same namespace
        $namespace = $reflection->getNamespaceName();
        $integrationDir = dirname($reflection->getFileName());

        $integrationData['triggers'] = $this->findTriggers($integrationDir, $namespace);
        $integrationData['actions'] = $this->findActions($integrationDir, $namespace);

        $this->integrations[$integrationId] = $integrationData;
    }

    /**
     * Find all triggers for an integration
     *
     * @return array<array<string, mixed>>
     */
    private function findTriggers(string $integrationDir, string $namespace): array
    {
        $triggersDir = $integrationDir . '/Triggers';

        if (!is_dir($triggersDir)) {
            return [];
        }

        $triggers = [];
        $classes = ClassDiscovery::discover($triggersDir, $namespace . '\\Triggers');

        foreach ($classes as $className) {
            try {
                if (!class_exists($className)) {
                    continue;
                }

                $reflection = new ReflectionClass($className);
                $attributes = $reflection->getAttributes(TriggerAttribute::class);

                if (!empty($attributes)) {
                    $trigger = $attributes[0]->newInstance();
                    $triggers[] = $trigger->toArray();
                }
            } catch (\Throwable $e) {
                echo "Warning: Could not process trigger {$className}: {$e->getMessage()}\n";
            }
        }

        return $triggers;
    }

    /**
     * Find all actions for an integration
     *
     * @return array<array<string, mixed>>
     */
    private function findActions(string $integrationDir, string $namespace): array
    {
        $actionsDir = $integrationDir . '/Actions';

        if (!is_dir($actionsDir)) {
            return [];
        }

        $actions = [];
        $classes = ClassDiscovery::discover($actionsDir, $namespace . '\\Actions');

        foreach ($classes as $className) {
            try {
                if (!class_exists($className)) {
                    continue;
                }

                $reflection = new ReflectionClass($className);
                $attributes = $reflection->getAttributes(ActionAttribute::class);

                if (!empty($attributes)) {
                    $action = $attributes[0]->newInstance();
                    $actions[] = $action->toArray();
                }
            } catch (\Throwable $e) {
                echo "Warning: Could not process action {$className}: {$e->getMessage()}\n";
            }
        }

        return $actions;
    }

    /**
     * Build the complete manifest structure
     *
     * @return array<string, mixed>
     */
    private function buildManifest(): array
    {
        // Sort integrations by ID for deterministic output
        ksort($this->integrations);

        // Sort triggers and actions within each integration
        foreach ($this->integrations as &$integration) {
            if (isset($integration['triggers'])) {
                usort($integration['triggers'], fn ($a, $b) => $a['id'] <=> $b['id']);
            }
            if (isset($integration['actions'])) {
                usort($integration['actions'], fn ($a, $b) => $a['id'] <=> $b['id']);
            }
        }
        unset($integration);

        $manifest = [
            'plugin' => 'dolliewp/sdk',
            'name' => 'Dollie Integrations SDK',
            'version' => $this->version,
            'generated_at' => (new \DateTimeImmutable())->format('Y-m-d\TH:i:s\Z'),
            'integrations' => array_values($this->integrations),
        ];

        // Calculate checksum
        $manifest['checksum'] = $this->calculateChecksum($manifest);

        return $manifest;
    }

    /**
     * Calculate SHA-256 checksum of manifest
     */
    private function calculateChecksum(array $manifest): string
    {
        // Remove checksum field if it exists
        unset($manifest['checksum']);

        // Create deterministic JSON
        $json = json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return 'sha256:' . hash('sha256', $json);
    }

    /**
     * Validate the manifest using Schema validator
     */
    private function validateManifest(array $manifest): void
    {
        $schema = new Schema();

        if (!$schema->validate($manifest)) {
            $errors = $schema->getFormattedErrors();
            throw new RuntimeException("Manifest validation failed:\n{$errors}");
        }

        echo "Manifest validation passed\n";
    }

    /**
     * Write manifest to files
     */
    public function write(array $manifest, string $outputDir): void
    {
        $outputDir = rtrim($outputDir, '/');

        // Create output directories
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        $integrationsDir = $outputDir . '/integrations';
        if (!is_dir($integrationsDir)) {
            mkdir($integrationsDir, 0755, true);
        }

        // Write main manifest
        $mainFile = $outputDir . '/manifest.json';
        echo "Writing main manifest to {$mainFile}\n";
        file_put_contents(
            $mainFile,
            json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        // Write per-integration files
        foreach ($manifest['integrations'] as $integration) {
            $slug = $integration['slug'];
            $file = $integrationsDir . '/' . $slug . '.json';
            echo "Writing integration manifest to {$file}\n";
            file_put_contents(
                $file,
                json_encode($integration, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
            );
        }

        echo "Manifest generation complete!\n";
    }
}
