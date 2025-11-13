<?php

declare(strict_types=1);

namespace Dollie\SDK\Attributes;

use Attribute;

/**
 * Action Attribute
 *
 * Marks a class as an action and defines its metadata.
 * Actions are operations that can be executed in response to triggers.
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Action
{
    /**
     * @param string $id Unique action identifier within the integration (e.g., 'woocommerce.order.update_status')
     * @param string $label Display label for the action
     * @param string|null $description Detailed description of what this action does
     * @param array<string, mixed>|null $inputSchema JSON Schema defining the action input parameters
     * @param array<string, mixed>|null $outputSchema JSON Schema defining the action output structure
     * @param array<array<string, mixed>> $examples Example inputs for documentation
     * @param array<string> $tags Categorization tags for filtering and search
     * @param string|null $since Version when this action was introduced
     */
    public function __construct(
        public readonly string $id,
        public readonly string $label,
        public readonly ?string $description = null,
        public readonly ?array $inputSchema = null,
        public readonly ?array $outputSchema = null,
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

        if ($this->inputSchema !== null) {
            $data['inputSchema'] = $this->inputSchema;
        }

        if ($this->outputSchema !== null) {
            $data['outputSchema'] = $this->outputSchema;
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
