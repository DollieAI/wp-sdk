<?php

declare(strict_types=1);

namespace Dollie\SDK\Build\Manifest;

/**
 * Manifest Schema Validator
 *
 * Validates the structure and content of the generated manifest.
 */
class Schema
{
    private array $errors = [];

    /**
     * Validate the complete manifest structure
     *
     * @param array<string, mixed> $manifest The manifest data to validate
     * @return bool True if valid, false otherwise
     */
    public function validate(array $manifest): bool
    {
        $this->errors = [];

        $this->validateRoot($manifest);

        if (!isset($manifest['integrations']) || !is_array($manifest['integrations'])) {
            return !$this->hasErrors();
        }

        foreach ($manifest['integrations'] as $index => $integration) {
            $this->validateIntegration($integration, $index);
        }

        return !$this->hasErrors();
    }

    /**
     * Validate root manifest fields
     */
    private function validateRoot(array $manifest): void
    {
        $required = ['plugin', 'name', 'version', 'generated_at', 'checksum', 'integrations'];

        foreach ($required as $field) {
            if (!isset($manifest[$field])) {
                $this->addError('root', "Missing required field: {$field}");
            }
        }

        if (isset($manifest['version']) && !$this->isValidVersion($manifest['version'])) {
            $this->addError('root', "Invalid version format: {$manifest['version']}");
        }
    }

    /**
     * Validate an integration structure
     */
    private function validateIntegration(array $integration, int $index): void
    {
        $required = ['id', 'name', 'slug'];

        foreach ($required as $field) {
            if (!isset($integration[$field]) || empty($integration[$field])) {
                $this->addError("integration[$index]", "Missing required field: {$field}");
            }
        }

        // Validate triggers
        if (isset($integration['triggers']) && is_array($integration['triggers'])) {
            $triggerIds = [];
            foreach ($integration['triggers'] as $tIndex => $trigger) {
                $this->validateTrigger($trigger, $integration['id'] ?? 'unknown', $tIndex);

                // Check for duplicate IDs
                if (isset($trigger['id'])) {
                    if (in_array($trigger['id'], $triggerIds, true)) {
                        $this->addError(
                            "integration[{$integration['id']}].triggers",
                            "Duplicate trigger ID: {$trigger['id']}"
                        );
                    }
                    $triggerIds[] = $trigger['id'];
                }
            }
        }

        // Validate actions
        if (isset($integration['actions']) && is_array($integration['actions'])) {
            $actionIds = [];
            foreach ($integration['actions'] as $aIndex => $action) {
                $this->validateAction($action, $integration['id'] ?? 'unknown', $aIndex);

                // Check for duplicate IDs
                if (isset($action['id'])) {
                    if (in_array($action['id'], $actionIds, true)) {
                        $this->addError(
                            "integration[{$integration['id']}].actions",
                            "Duplicate action ID: {$action['id']}"
                        );
                    }
                    $actionIds[] = $action['id'];
                }
            }
        }
    }

    /**
     * Validate a trigger structure
     */
    private function validateTrigger(array $trigger, string $integrationId, int $index): void
    {
        if (!isset($trigger['id']) || empty($trigger['id'])) {
            $this->addError("integration[$integrationId].triggers[$index]", 'Missing trigger ID');
        }

        if (!isset($trigger['label']) || empty($trigger['label'])) {
            $this->addError("integration[$integrationId].triggers[$index]", 'Missing trigger label');
        }

        // Validate schemas if present
        if (isset($trigger['payloadSchema']) && !is_array($trigger['payloadSchema'])) {
            $this->addError(
                "integration[$integrationId].triggers[$index]",
                'payloadSchema must be an array'
            );
        }
    }

    /**
     * Validate an action structure
     */
    private function validateAction(array $action, string $integrationId, int $index): void
    {
        if (!isset($action['id']) || empty($action['id'])) {
            $this->addError("integration[$integrationId].actions[$index]", 'Missing action ID');
        }

        if (!isset($action['label']) || empty($action['label'])) {
            $this->addError("integration[$integrationId].actions[$index]", 'Missing action label');
        }

        // Validate schemas if present
        if (isset($action['inputSchema']) && !is_array($action['inputSchema'])) {
            $this->addError(
                "integration[$integrationId].actions[$index]",
                'inputSchema must be an array'
            );
        }

        if (isset($action['outputSchema']) && !is_array($action['outputSchema'])) {
            $this->addError(
                "integration[$integrationId].actions[$index]",
                'outputSchema must be an array'
            );
        }
    }

    /**
     * Check if version string is valid semantic versioning
     */
    private function isValidVersion(string $version): bool
    {
        return (bool) preg_match('/^\d+\.\d+\.\d+(?:-[a-zA-Z0-9.-]+)?(?:\+[a-zA-Z0-9.-]+)?$/', $version);
    }

    /**
     * Add a validation error
     */
    private function addError(string $path, string $message): void
    {
        $this->errors[] = [
            'path' => $path,
            'message' => $message
        ];
    }

    /**
     * Check if there are any validation errors
     */
    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Get all validation errors
     *
     * @return array<array{path: string, message: string}>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get formatted error messages
     */
    public function getFormattedErrors(): string
    {
        if (empty($this->errors)) {
            return '';
        }

        $messages = [];
        foreach ($this->errors as $error) {
            $messages[] = "[{$error['path']}] {$error['message']}";
        }

        return implode("\n", $messages);
    }
}
