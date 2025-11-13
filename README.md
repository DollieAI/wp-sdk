# Dollie SDK

> Manifest-first integration framework for WordPress automations

## Overview

The Dollie SDK is a modern PHP 8.2+ framework for building WordPress integration automations. It uses a **manifest-first architecture** where metadata about integrations, triggers, and actions is generated at build time and consumed by external systems (like Laravel) without runtime code execution.

## Features

- **PHP 8 Attributes**: Modern, type-safe metadata definition
- **70+ Built-in Integrations**: WooCommerce, WordPress, Dollie, and more
- **Manifest Generation**: Automated JSON metadata export
- **Developer-Friendly**: Simple API for creating custom integrations
- **CI/CD Ready**: Automated builds and validation

## Installation

### Using Composer (if PHP 8.2+ is available)

```bash
composer require dolliewp/sdk
```

### Using Docker (Recommended for Local Development)

If you don't have PHP 8.2+ installed locally:

```bash
# Install dependencies
make install

# Generate manifest
make generate

# Run tests
make test
```

See [DOCKER.md](DOCKER.md) for complete Docker development guide.

## Quick Start

### Using Existing Integrations

The SDK includes 70+ pre-built integrations. After installation, generate the manifest:

**With Composer:**
```bash
composer generate-manifest
```

**With Docker:**
```bash
make generate
```

This creates:
- `dist/manifest.json` - Complete manifest of all integrations
- `dist/integrations/*.json` - Per-integration metadata files

### Creating a Custom Integration

```php
<?php

namespace MyPlugin\Integrations\CustomService;

use Dollie\SDK\Attributes\Integration;

#[Integration(
    id: 'custom_service',
    name: 'Custom Service',
    slug: 'custom-service',
    since: '1.0.0',
    homepage: 'https://example.com',
    tags: ['custom']
)]
class CustomService
{
    public function is_plugin_installed(): bool
    {
        return class_exists('CustomServicePlugin');
    }
}
```

### Creating a Trigger

```php
<?php

namespace MyPlugin\Integrations\CustomService\Triggers;

use Dollie\SDK\Attributes\Trigger;

#[Trigger(
    id: 'custom_service.event_occurred',
    label: 'Event Occurred',
    description: 'Fires when a custom event occurs',
    payloadSchema: [
        'type' => 'object',
        'required' => ['event_id'],
        'properties' => [
            'event_id' => ['type' => 'integer'],
            'event_type' => ['type' => 'string']
        ]
    ],
    examples: [
        ['event_id' => 123, 'event_type' => 'custom']
    ],
    tags: ['events'],
    since: '1.0.0'
)]
class EventOccurred
{
    // Trigger implementation
}
```

### Creating an Action

```php
<?php

namespace MyPlugin\Integrations\CustomService\Actions;

use Dollie\SDK\Attributes\Action;

#[Action(
    id: 'custom_service.create_item',
    label: 'Create Item',
    description: 'Creates a new item in the service',
    inputSchema: [
        'type' => 'object',
        'required' => ['name'],
        'properties' => [
            'name' => ['type' => 'string'],
            'description' => ['type' => 'string']
        ]
    ],
    outputSchema: [
        'type' => 'object',
        'properties' => [
            'success' => ['type' => 'boolean'],
            'item_id' => ['type' => 'integer']
        ]
    ],
    examples: [
        ['name' => 'Test Item', 'description' => 'A test item']
    ],
    tags: ['items'],
    since: '1.0.0'
)]
class CreateItem
{
    // Action implementation
}
```

## Manifest Structure

The generated manifest follows this structure:

```json
{
  "plugin": "dolliewp/sdk",
  "name": "Dollie Integrations SDK",
  "version": "1.0.0",
  "generated_at": "2025-11-12T10:30:00Z",
  "checksum": "sha256:...",
  "integrations": [
    {
      "id": "woocommerce",
      "name": "WooCommerce",
      "slug": "woocommerce",
      "since": "1.0.0",
      "homepage": "https://woocommerce.com",
      "tags": ["commerce"],
      "triggers": [...],
      "actions": [...]
    }
  ]
}
```

## Development

### Docker Development (Recommended)

If you don't have PHP 8.2+ installed:

```bash
# Initial setup
make setup

# Generate manifest
make generate

# Run tests
make test

# Validate everything
make ci

# Open shell for debugging
make shell
```

See [DOCKER.md](DOCKER.md) for complete guide.

### Local PHP Development

If you have PHP 8.2+ and Composer installed:

```bash
# Install dependencies
composer install

# Generate manifest
composer generate-manifest

# Run tests
composer test
```

### Validate Manifest

The manifest generator automatically validates:
- Attribute presence and correctness
- Unique IDs within each integration
- Required field completeness
- Schema structure validity

## Architecture

This SDK follows a **manifest-first** approach:

1. **Build Time**: Attributes are scanned and converted to JSON manifest
2. **Distribution**: Manifest files are published as artifacts
3. **Consumption**: External systems (Laravel) import manifest metadata
4. **Zero Runtime Coupling**: No code execution in consuming systems

## Contributing

Contributions are welcome! Please see our contributing guide for details.

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Links

- [Documentation](https://docs.getdollie.com)
- [GitHub Repository](https://github.com/dolliewp/dollie-sdk)
- [Dollie Website](https://getdollie.com)
