# Dollie SDK

> Manifest-first integration framework for WordPress automations

## Overview

The Dollie SDK is a modern PHP 8.2+ framework for building WordPress integration automations. It provides **base classes, attributes, and controllers** that enable developers to create custom integrations with triggers and actions. The SDK uses a **manifest-first architecture** where metadata is generated at build time and consumed by external systems (like Laravel) without runtime code execution.

## Features

- **PHP 8 Attributes**: Modern, type-safe metadata definition for integrations, triggers, and actions
- **Base Classes**: `BaseIntegration` and `AutomateAction` abstract classes for building integrations
- **Controllers**: `IntegrationsController` and `AutomationController` for runtime management
- **Singleton Trait**: Ready-to-use `SingletonLoader` trait for class instances
- **Manifest Generation**: Automated JSON metadata export
- **Docker Development**: Complete Docker-based development environment
- **CI/CD Ready**: Automated builds and validation

## Installation

```bash
composer require dollieai/wp-sdk
```

**Requirements:** PHP 8.2+

## Quick Start

### Creating a Custom Integration

```php
<?php

namespace MyPlugin\Integrations\CustomService;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Base\BaseIntegration;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'custom_service',
    name: 'Custom Service',
    slug: 'custom-service',
    since: '1.0.0',
    homepage: 'https://example.com',
    tags: ['custom']
)]
class CustomService extends BaseIntegration
{
    use SingletonLoader;

    protected $id = 'custom_service';
    protected $name = 'Custom Service';

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
use Dollie\SDK\Base\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;

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
class CreateItem extends AutomateAction
{
    use SingletonLoader;

    public $integration = 'custom_service';
    public $action = 'create_item';

    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => 'Create Item',
            'action' => $this->action,
            'function' => [$this, '_action_listener'],
        ];
        return $actions;
    }

    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        // Action implementation
        return ['success' => true, 'item_id' => 123];
    }
}
```

## SDK Structure

The SDK provides the following components:

```
src/
├── Attributes/
│   ├── Integration.php    # #[Integration] attribute for marking integration classes
│   ├── Trigger.php        # #[Trigger] attribute for defining trigger metadata
│   └── Action.php         # #[Action] attribute for defining action metadata
├── Base/
│   ├── BaseIntegration.php    # Abstract base class for integrations
│   └── AutomateAction.php     # Abstract base class for actions
├── Controllers/
│   ├── IntegrationsController.php  # Manages integration registration
│   └── AutomationController.php    # Handles trigger execution
└── Traits/
    └── SingletonLoader.php    # Singleton pattern trait
```

## Manifest Generation

Plugins that use this SDK can generate manifests by scanning classes with SDK attributes. The generated manifest structure:

```json
{
  "plugin": "your-plugin/name",
  "name": "Your Plugin Name",
  "version": "1.0.0",
  "generated_at": "2025-11-12T10:30:00Z",
  "checksum": "sha256:...",
  "integrations": [
    {
      "id": "custom_service",
      "name": "Custom Service",
      "slug": "custom-service",
      "since": "1.0.0",
      "homepage": "https://example.com",
      "tags": ["custom"],
      "triggers": [...],
      "actions": [...]
    }
  ]
}
```

The SDK includes manifest generation scripts in `scripts/` that can be adapted for your plugin.

## Architecture

This SDK follows a **manifest-first** approach:

1. **Build Time**: Attributes are scanned and converted to JSON manifest
2. **Distribution**: Manifest files are published as artifacts
3. **Consumption**: External systems (Laravel) import manifest metadata
4. **Runtime**: Controllers manage integration registration and trigger execution

## Development

### Running Tests

```bash
# Using Docker
make test

# Using local PHP
composer test
```

### Available Make Commands

```bash
make install    # Install dependencies
make test       # Run PHPUnit tests
make validate   # Validate composer.json
make shell      # Open a shell in the PHP container
make clean      # Remove generated files
make ci         # Run all CI checks
```

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Links

- [Documentation](https://docs.getdollie.com)
- [GitHub Repository](https://github.com/DollieAI/wp-sdk)
- [Dollie Website](https://getdollie.com)
