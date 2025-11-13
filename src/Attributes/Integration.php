<?php

declare(strict_types=1);

namespace Dollie\SDK\Attributes;

use Attribute;

/**
 * Integration Attribute
 *
 * Marks a class as an integration and defines its metadata.
 * This metadata is extracted during build time to generate the manifest.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Integration
{
    /**
     * @param string $id Unique integration identifier (e.g., 'woocommerce', 'dollie')
     * @param string $name Display name of the integration
     * @param string|null $slug URL-friendly slug (defaults to id if not provided)
     * @param string|null $since Version when this integration was introduced
     * @param string|null $homepage Homepage URL for the integration
     * @param array<string> $tags Categorization tags for filtering and search
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $slug = null,
        public readonly ?string $since = null,
        public readonly ?string $homepage = null,
        public readonly array $tags = []
    ) {
    }

    /**
     * Get the slug, falling back to ID if not provided
     */
    public function getSlug(): string
    {
        return $this->slug ?? $this->id;
    }

    /**
     * Convert to array for manifest generation
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->getSlug(),
        ];

        if ($this->since !== null) {
            $data['since'] = $this->since;
        }

        if ($this->homepage !== null) {
            $data['homepage'] = $this->homepage;
        }

        if (!empty($this->tags)) {
            $data['tags'] = $this->tags;
        }

        return $data;
    }
}
