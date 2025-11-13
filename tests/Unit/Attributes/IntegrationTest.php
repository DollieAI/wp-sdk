<?php

declare(strict_types=1);

namespace Dollie\SDK\Tests\Unit\Attributes;

use Dollie\SDK\Attributes\Integration;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function test_integration_attribute_can_be_instantiated(): void
    {
        $integration = new Integration(
            id: 'test_integration',
            name: 'Test Integration',
            slug: 'test-integration',
            since: '1.0.0',
            homepage: 'https://example.com',
            tags: ['test']
        );

        $this->assertSame('test_integration', $integration->id);
        $this->assertSame('Test Integration', $integration->name);
        $this->assertSame('test-integration', $integration->slug);
        $this->assertSame('1.0.0', $integration->since);
        $this->assertSame('https://example.com', $integration->homepage);
        $this->assertSame(['test'], $integration->tags);
    }

    public function test_slug_defaults_to_id_when_not_provided(): void
    {
        $integration = new Integration(
            id: 'my_integration',
            name: 'My Integration'
        );

        $this->assertSame('my_integration', $integration->getSlug());
    }

    public function test_to_array_includes_all_fields(): void
    {
        $integration = new Integration(
            id: 'test',
            name: 'Test',
            slug: 'test-slug',
            since: '1.0.0',
            homepage: 'https://example.com',
            tags: ['tag1', 'tag2']
        );

        $array = $integration->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('slug', $array);
        $this->assertArrayHasKey('since', $array);
        $this->assertArrayHasKey('homepage', $array);
        $this->assertArrayHasKey('tags', $array);
    }

    public function test_to_array_omits_null_values(): void
    {
        $integration = new Integration(
            id: 'test',
            name: 'Test'
        );

        $array = $integration->toArray();

        $this->assertArrayNotHasKey('since', $array);
        $this->assertArrayNotHasKey('homepage', $array);
        $this->assertArrayNotHasKey('tags', $array);
    }
}
