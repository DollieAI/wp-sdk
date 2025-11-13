<?php

declare(strict_types=1);

namespace Dollie\SDK\Attributes;

use Attribute;

/**
 * Trigger Attribute
 *
 * Marks a class as a trigger and defines its metadata.
 * Triggers are events that fire when specific conditions are met.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Trigger
{
    /**
     * @param string $id Unique trigger identifier within the integration (e.g., 'woocommerce.order.created')
     * @param string $label Display label for the trigger
     * @param string|null $description Detailed description of when this trigger fires
     * @param array<string, mixed>|null $payloadSchema JSON Schema defining the trigger payload structure
     * @param array<array<string, mixed>> $examples Example payloads for documentation
     * @param array<string> $tags Categorization tags for filtering and search
     * @param string|null $since Version when this trigger was introduced
     */
    public function __construct(
        public readonly string $id,
        public readonly string $label,
        public readonly ?string $description = null,
        public readonly ?array $payloadSchema = null,
        public readonly array $examples = [],
        public readonly array $tags = [],
        public readonly ?string $since = null
    ) {
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
            'label' => $this->label,
        ];

        if ($this->description !== null) {
            $data['description'] = $this->description;
        }

        if ($this->payloadSchema !== null) {
            $data['payloadSchema'] = $this->payloadSchema;
        }

        if (!empty($this->examples)) {
            $data['examples'] = $this->examples;
        }

        if (!empty($this->tags)) {
            $data['tags'] = $this->tags;
        }

        if ($this->since !== null) {
            $data['since'] = $this->since;
        }

        return $data;
    }
}
