# Dollie SDK - Developer Guide

Welcome to the Dollie SDK Developer Guide. This document provides comprehensive information for developers who want to create custom integrations using the SDK.

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Getting Started](#getting-started)
4. [Creating Integrations](#creating-integrations)
5. [Creating Triggers](#creating-triggers)
6. [Creating Actions](#creating-actions)
7. [Manifest Generation](#manifest-generation)
8. [Testing](#testing)
9. [Best Practices](#best-practices)
10. [Publishing](#publishing)

---

## Overview

The Dollie SDK uses a **manifest-first architecture**:

1. **Build Time**: PHP 8 Attributes are scanned and converted to JSON manifest
2. **Distribution**: Manifest files are published as artifacts
3. **Consumption**: External systems (Laravel) import manifest metadata
4. **Runtime**: WordPress executes the actual integration code

This separation allows the Laravel documentation app to import metadata without executing WordPress code.

---

## Architecture

### Components

```
┌─────────────────────────────────────────────┐
│          PHP 8 Attributes                   │
│  (Integration, Trigger, Action)             │
└─────────────┬───────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────┐
│      Manifest Generator                     │
│  (ClassDiscovery → Schema → JSON)           │
└─────────────┬───────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────┐
│         dist/manifest.json                  │
│   dist/integrations/*.json                  │
└─────────────┬───────────────────────────────┘
              │
              ├──────────────────┬─────────────┐
              ▼                  ▼             ▼
      ┌──────────────┐   ┌─────────────┐  ┌──────────┐
      │   Laravel    │   │   CI/CD     │  │  Docs    │
      │   Importer   │   │   Pipeline  │  │  Site    │
      └──────────────┘   └─────────────┘  └──────────┘
```

### File Structure

```
dollie-sdk/
├── src/
│   ├── Attributes/           # Attribute definitions
│   │   ├── Integration.php
│   │   ├── Trigger.php
│   │   └── Action.php
│   └── Integrations/         # Integration implementations
│       └── {IntegrationName}/
│           ├── {IntegrationName}.php
│           ├── Triggers/
│           │   └── *.php
│           └── Actions/
│               └── *.php
├── scripts/
│   ├── generate-manifest.php  # Entry point
│   └── src/Manifest/
│       ├── Generator.php       # Main generator
│       ├── Schema.php          # Validator
│       └── Support/
│           └── ClassDiscovery.php
├── dist/
│   ├── manifest.json          # Combined manifest
│   └── integrations/          # Per-integration files
│       └── {slug}.json
└── tests/
    ├── Unit/
    └── Integration/
```

---

## Getting Started

### Prerequisites

- PHP 8.2 or higher
- Composer
- WordPress environment (for runtime execution)

### Installation

```bash
composer require dolliewp/sdk
```

### Development Setup

1. Clone the repository:
```bash
git clone https://github.com/dolliewp/dollie-sdk.git
cd dollie-sdk
```

2. Install dependencies:
```bash
composer install
```

3. Generate the manifest:
```bash
composer generate-manifest
```

---

## Creating Integrations

An integration represents a plugin, service, or platform that you want to automate.

### Basic Integration

```php
<?php

namespace MyPlugin\Integrations\MyService;

use Dollie\SDK\Attributes\Integration;

#[Integration(
    id: 'my_service',
    name: 'My Service',
    slug: 'my-service',
    since: '1.0.0',
    homepage: 'https://myservice.com',
    tags: ['crm', 'email']
)]
class MyService
{
    public function is_plugin_installed(): bool
    {
        return class_exists('MyServicePlugin')
            || function_exists('my_service_init');
    }
}
```

### Attribute Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | string | Yes | Unique integration identifier |
| `name` | string | Yes | Human-readable name |
| `slug` | string | No | URL-friendly slug (defaults to id) |
| `since` | string | No | Version when introduced |
| `homepage` | string | No | Integration homepage URL |
| `tags` | array | No | Categorization tags |

### Naming Conventions

- **ID**: lowercase, underscores (e.g., `woocommerce`, `dollie_hub`)
- **Name**: Title Case (e.g., `WooCommerce`, `Dollie Hub`)
- **Slug**: lowercase, hyphens (e.g., `woocommerce`, `dollie-hub`)

---

## Creating Triggers

Triggers fire when specific events occur in your integration.

### Basic Trigger

```php
<?php

namespace MyPlugin\Integrations\MyService\Triggers;

use Dollie\SDK\Attributes\Trigger;

#[Trigger(
    id: 'my_service.user.registered',
    label: 'User Registered',
    description: 'Fires when a new user registers in My Service',
    payloadSchema: [
        'type' => 'object',
        'required' => ['user_id', 'email'],
        'properties' => [
            'user_id' => [
                'type' => 'integer',
                'description' => 'Unique user ID'
            ],
            'email' => [
                'type' => 'string',
                'format' => 'email',
                'description' => 'User email address'
            ],
            'name' => [
                'type' => 'string',
                'description' => 'User full name'
            ],
            'registered_at' => [
                'type' => 'string',
                'format' => 'date-time',
                'description' => 'Registration timestamp'
            ]
        ]
    ],
    examples: [
        [
            'user_id' => 123,
            'email' => 'user@example.com',
            'name' => 'John Doe',
            'registered_at' => '2025-11-12T10:30:00Z'
        ]
    ],
    tags: ['users', 'registration'],
    since: '1.0.0'
)]
class UserRegistered
{
    public string $integration = 'my_service';
    public string $trigger = 'my_service.user.registered';

    public function __construct()
    {
        add_action('my_service_user_registered', [$this, 'trigger_listener'], 10, 2);
    }

    public function trigger_listener($user_id, $user_data): void
    {
        $context = [
            'user_id' => $user_id,
            'email' => $user_data['email'],
            'name' => $user_data['name'],
            'registered_at' => current_time('c')
        ];

        // Fire the trigger (implementation depends on your platform)
        // AutomationController::dollie_trigger_handle_trigger($context);
    }
}
```

### Payload Schema

The payload schema defines what data your trigger provides. Use JSON Schema format:

```php
payloadSchema: [
    'type' => 'object',
    'required' => ['required_field'],
    'properties' => [
        'string_field' => [
            'type' => 'string',
            'description' => 'A text field',
            'minLength' => 1,
            'maxLength' => 255
        ],
        'number_field' => [
            'type' => 'integer',
            'description' => 'A number field',
            'minimum' => 0
        ],
        'enum_field' => [
            'type' => 'string',
            'enum' => ['option1', 'option2', 'option3'],
            'description' => 'Field with predefined options'
        ],
        'date_field' => [
            'type' => 'string',
            'format' => 'date-time',
            'description' => 'ISO 8601 date-time'
        ],
        'object_field' => [
            'type' => 'object',
            'properties' => [
                'nested_field' => ['type' => 'string']
            ]
        ],
        'array_field' => [
            'type' => 'array',
            'items' => ['type' => 'string']
        ]
    ]
]
```

---

## Creating Actions

Actions perform operations in response to triggers.

### Basic Action

```php
<?php

namespace MyPlugin\Integrations\MyService\Actions;

use Dollie\SDK\Attributes\Action;

#[Action(
    id: 'my_service.user.create',
    label: 'Create User',
    description: 'Creates a new user in My Service',
    inputSchema: [
        'type' => 'object',
        'required' => ['email', 'name'],
        'properties' => [
            'email' => [
                'type' => 'string',
                'format' => 'email',
                'description' => 'User email address'
            ],
            'name' => [
                'type' => 'string',
                'description' => 'User full name',
                'minLength' => 1
            ],
            'role' => [
                'type' => 'string',
                'enum' => ['user', 'admin', 'moderator'],
                'default' => 'user',
                'description' => 'User role'
            ]
        ]
    ],
    outputSchema: [
        'type' => 'object',
        'properties' => [
            'success' => [
                'type' => 'boolean',
                'description' => 'Whether the operation succeeded'
            ],
            'user_id' => [
                'type' => 'integer',
                'description' => 'ID of created user'
            ],
            'message' => [
                'type' => 'string',
                'description' => 'Success or error message'
            ]
        ]
    ],
    examples: [
        [
            'email' => 'newuser@example.com',
            'name' => 'Jane Smith',
            'role' => 'user'
        ]
    ],
    tags: ['users', 'create'],
    since: '1.0.0'
)]
class CreateUser
{
    public string $integration = 'my_service';
    public string $action = 'my_service.user.create';

    public function _action_listener(int $user_id, int $automation_id, array $fields, array $selected_options): array
    {
        // Validate required fields
        if (empty($selected_options['email']) || empty($selected_options['name'])) {
            return [
                'success' => false,
                'message' => 'Email and name are required'
            ];
        }

        // Perform the action
        try {
            $new_user_id = $this->createUserInService(
                $selected_options['email'],
                $selected_options['name'],
                $selected_options['role'] ?? 'user'
            );

            return [
                'success' => true,
                'user_id' => $new_user_id,
                'message' => 'User created successfully'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create user: ' . $e->getMessage()
            ];
        }
    }

    private function createUserInService(string $email, string $name, string $role): int
    {
        // Your implementation
        return 123;
    }
}
```

### Input/Output Schemas

Define clear contracts for your actions:

```php
inputSchema: [
    'type' => 'object',
    'required' => ['required_param'],
    'properties' => [
        'required_param' => [
            'type' => 'string',
            'description' => 'This parameter is required'
        ],
        'optional_param' => [
            'type' => 'integer',
            'default' => 0,
            'description' => 'This parameter is optional'
        ]
    ]
],
outputSchema: [
    'type' => 'object',
    'properties' => [
        'success' => ['type' => 'boolean'],
        'data' => [
            'type' => 'object',
            'description' => 'Response data'
        ],
        'message' => ['type' => 'string']
    ]
]
```

---

## Manifest Generation

### How It Works

1. **Discovery**: Scans `src/Integrations` for PHP classes
2. **Attribute Extraction**: Reads `#[Integration]`, `#[Trigger]`, `#[Action]` attributes
3. **Validation**: Checks for duplicate IDs and required fields
4. **Sorting**: Sorts deterministically for consistent output
5. **Checksum**: Calculates SHA-256 checksum of manifest
6. **Output**: Writes `dist/manifest.json` and `dist/integrations/*.json`

### Running Manually

```bash
composer generate-manifest
```

### Automatic Generation

The manifest regenerates automatically when you run:
```bash
composer install
composer dump-autoload
```

### CI/CD

GitHub Actions automatically generates the manifest on every push to main:
- Validates code and attributes
- Generates manifest
- Commits back to repository
- Creates release artifacts

---

## Testing

### Unit Tests

Test your attributes and classes:

```bash
composer test
```

### Manual Testing

1. Generate manifest:
```bash
composer generate-manifest
```

2. Check your integration file:
```bash
cat dist/integrations/my-service.json | jq '.'
```

3. Verify no duplicate IDs:
```bash
composer validate-manifest
```

### Integration Testing

Test in WordPress environment:

1. Install your plugin with integration
2. Trigger events and verify triggers fire
3. Execute actions and verify responses
4. Check WordPress logs for errors

---

## Best Practices

### ID Format

Use dot notation for hierarchical organization:
```
integration_id.entity.action

Examples:
- woocommerce.order.created
- dollie.site.deployed
- my_service.user.updated
```

### Versioning

Use semantic versioning in `since` fields:
- **1.0.0**: Initial release
- **1.1.0**: New triggers/actions added
- **2.0.0**: Breaking changes

### Descriptions

Write clear, actionable descriptions:
- **Trigger**: "Fires when..."
- **Action**: "Creates/Updates/Deletes..."
- **Field**: "The unique identifier for..."

### Examples

Provide realistic, helpful examples:
```php
examples: [
    [
        'user_id' => 123,
        'email' => 'user@example.com',
        'status' => 'active'
    ]
]
```

### Error Handling

Always return structured error responses:
```php
return [
    'success' => false,
    'message' => 'Clear error message',
    'error_code' => 'VALIDATION_ERROR'
];
```

### Tags

Use consistent, lowercase tags:
- Entity types: `users`, `orders`, `posts`
- Actions: `create`, `update`, `delete`
- Categories: `ecommerce`, `crm`, `email`

---

## Publishing

### Package Distribution

1. Ensure tests pass:
```bash
composer test
```

2. Generate manifest:
```bash
composer generate-manifest
```

3. Commit changes:
```bash
git add .
git commit -m "feat: add new integration"
git push
```

4. Create release:
```bash
git tag v1.0.0
git push --tags
```

### GitHub Release

CI/CD automatically:
- Generates manifest
- Runs tests
- Creates release with manifest artifacts

### Packagist

The package is available on Packagist as `dolliewp/sdk`.

---

## Support

- **Documentation**: [docs.getdollie.com](https://docs.getdollie.com)
- **GitHub Issues**: [github.com/dolliewp/dollie-sdk/issues](https://github.com/dolliewp/dollie-sdk/issues)
- **Community**: [Dollie Community](https://getdollie.com/community)

---

## License

MIT License - See [LICENSE](LICENSE) file for details.
