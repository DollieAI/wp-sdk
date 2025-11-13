<?php

declare(strict_types=1);

namespace Dollie\SDK\Tests\Unit\Manifest;

use Dollie\SDK\Build\Manifest\Schema;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    private Schema $schema;

    protected function setUp(): void
    {
        $this->schema = new Schema();
    }

    public function test_validate_accepts_valid_manifest(): void
    {
        $manifest = [
            'plugin' => 'dolliewp/sdk',
            'name' => 'Test SDK',
            'version' => '1.0.0',
            'generated_at' => '2025-11-12T10:00:00Z',
            'checksum' => 'sha256:abc123',
            'integrations' => [
                [
                    'id' => 'test',
                    'name' => 'Test Integration',
                    'slug' => 'test',
                    'triggers' => [],
                    'actions' => []
                ]
            ]
        ];

        $result = $this->schema->validate($manifest);

        $this->assertTrue($result);
        $this->assertFalse($this->schema->hasErrors());
    }

    public function test_validate_rejects_missing_required_fields(): void
    {
        $manifest = [
            'plugin' => 'dolliewp/sdk',
            // Missing other required fields
        ];

        $result = $this->schema->validate($manifest);

        $this->assertFalse($result);
        $this->assertTrue($this->schema->hasErrors());

        $errors = $this->schema->getErrors();
        $this->assertNotEmpty($errors);
    }

    public function test_validate_rejects_invalid_version(): void
    {
        $manifest = [
            'plugin' => 'dolliewp/sdk',
            'name' => 'Test',
            'version' => 'not-a-version',
            'generated_at' => '2025-11-12T10:00:00Z',
            'checksum' => 'sha256:abc',
            'integrations' => []
        ];

        $result = $this->schema->validate($manifest);

        $this->assertFalse($result);
        $errors = $this->schema->getFormattedErrors();
        $this->assertStringContainsString('version', $errors);
    }

    public function test_validate_detects_duplicate_trigger_ids(): void
    {
        $manifest = [
            'plugin' => 'dolliewp/sdk',
            'name' => 'Test',
            'version' => '1.0.0',
            'generated_at' => '2025-11-12T10:00:00Z',
            'checksum' => 'sha256:abc',
            'integrations' => [
                [
                    'id' => 'test',
                    'name' => 'Test',
                    'slug' => 'test',
                    'triggers' => [
                        ['id' => 'trigger_1', 'label' => 'Trigger 1'],
                        ['id' => 'trigger_1', 'label' => 'Trigger 1 Duplicate']
                    ]
                ]
            ]
        ];

        $result = $this->schema->validate($manifest);

        $this->assertFalse($result);
        $errors = $this->schema->getFormattedErrors();
        $this->assertStringContainsString('Duplicate trigger ID', $errors);
    }

    public function test_validate_detects_duplicate_action_ids(): void
    {
        $manifest = [
            'plugin' => 'dolliewp/sdk',
            'name' => 'Test',
            'version' => '1.0.0',
            'generated_at' => '2025-11-12T10:00:00Z',
            'checksum' => 'sha256:abc',
            'integrations' => [
                [
                    'id' => 'test',
                    'name' => 'Test',
                    'slug' => 'test',
                    'actions' => [
                        ['id' => 'action_1', 'label' => 'Action 1'],
                        ['id' => 'action_1', 'label' => 'Action 1 Duplicate']
                    ]
                ]
            ]
        ];

        $result = $this->schema->validate($manifest);

        $this->assertFalse($result);
        $errors = $this->schema->getFormattedErrors();
        $this->assertStringContainsString('Duplicate action ID', $errors);
    }
}
