# Dollie SDK - Integration Guide

This guide explains how to integrate the Dollie SDK into your WordPress plugin or as a standalone Composer package.

## Table of Contents

1. [Installation](#installation)
2. [As a WordPress Plugin](#as-a-wordpress-plugin)
3. [As a Composer Package](#as-a-composer-package)
4. [Usage in Your Plugin](#usage-in-your-plugin)
5. [Creating New Integrations](#creating-new-integrations)
6. [Generating the Manifest](#generating-the-manifest)

## Installation

### Option 1: As a Composer Dependency

```bash
composer require dolliewp/sdk
```

### Option 2: Clone Repository

```bash
git clone https://github.com/dolliewp/dollie-sdk.git
cd dollie-sdk
composer install
```

### As a Library in Another Plugin

If you want to include the SDK as a library in your existing WordPress plugin:

```php
<?php
// In your plugin's main file
require_once __DIR__ . '/lib/dollie-sdk/vendor/autoload.php';

// Access integrations
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Controllers\AutomationController;

// Get all registered integrations
$integrations = IntegrationsController::get_integrations();

// Fire a trigger
AutomationController::dollie_trigger_handle_trigger([
    'trigger' => 'wordpress.user.created',
    'context' => [
        'wp_user_id' => 123,
        'user_email' => 'user@example.com',
    ],
]);
```

## As a Composer Package

The SDK is designed to work as a standalone Composer package that can be required in any WordPress project:

### 1. Add to your composer.json

```json
{
    "require": {
        "dolliewp/sdk": "^1.0"
    }
}
```

### 2. Use in Your Code

```php
<?php
use Dollie\SDK\Controllers\IntegrationsController;

// Load via Composer autoloader (WordPress handles this)
// All integrations are automatically registered

// Access integrations
$wordpress = IntegrationsController::get_integration('WordPress');
if ($wordpress && $wordpress->is_plugin_installed()) {
    // WordPress integration is available
}
```

## Usage in Your Plugin

### Listening to Triggers

```php
<?php
use Dollie\SDK\Controllers\AutomationController;

// Listen to triggers
AutomationController::add_listener('wordpress.user.created', function($context) {
    // Handle user creation
    $user_id = $context['wp_user_id'];
    $email = $context['user_email'];

    // Your custom logic here
    error_log("New user created: {$email}");
});
```

### Executing Actions

Actions are registered automatically. To execute an action programmatically:

```php
<?php
use Dollie\SDK\Controllers\IntegrationsController;

// Get WordPress integration
$wordpress = IntegrationsController::get_integration('WordPress');

// Actions are available through the integration
// Each action class has a _action_listener method
```

### Checking Available Integrations

```php
<?php
use Dollie\SDK\Controllers\IntegrationsController;

// Get all integrations
$integrations = IntegrationsController::get_integrations();

foreach ($integrations as $id => $integration) {
    echo "Integration: {$id}\n";
    echo "Installed: " . ($integration->is_plugin_installed() ? 'Yes' : 'No') . "\n";
}
```

## Creating New Integrations

### 1. Create Integration Directory

```
src/Integrations/MyIntegration/
├── MyIntegration.php
├── Triggers/
│   └── MyTrigger.php
└── Actions/
    └── MyAction.php
```

### 2. Create Integration Class

```php
<?php
namespace Dollie\SDK\Integrations\MyIntegration;

use Dollie\SDK\Attributes\Integration;
use Dollie\SDK\Integrations\BaseIntegration;
use Dollie\SDK\Controllers\IntegrationsController;
use Dollie\SDK\Traits\SingletonLoader;

#[Integration(
    id: 'my_integration',
    name: 'My Integration',
    slug: 'my-integration',
    since: '1.0.0',
    homepage: 'https://example.com',
    tags: ['custom', 'example']
)]
class MyIntegration extends BaseIntegration
{
    use SingletonLoader;

    protected $id = 'my_integration';

    public function is_plugin_installed(): bool
    {
        return class_exists('MyPlugin');
    }
}

// Register the integration
IntegrationsController::register(MyIntegration::class);
```

### 3. Create Trigger

```php
<?php
namespace Dollie\SDK\Integrations\MyIntegration\Triggers;

use Dollie\SDK\Attributes\Trigger;
use Dollie\SDK\Controllers\AutomationController;
use Dollie\SDK\Traits\SingletonLoader;

#[Trigger(
    id: 'my_integration.something_happened',
    label: 'Something Happened',
    description: 'Fires when something happens',
    payloadSchema: [
        'type' => 'object',
        'properties' => [
            'data' => ['type' => 'string', 'description' => 'Event data']
        ]
    ],
    tags: ['events'],
    since: '1.0.0'
)]
class MyTrigger
{
    use SingletonLoader;

    public $integration = 'my_integration';
    public $trigger = 'something_happened';

    public function __construct()
    {
        add_filter('dollie_trigger_register_trigger', [$this, 'register']);
        add_action('my_plugin_event', [$this, 'trigger_listener']);
    }

    public function register($triggers)
    {
        $triggers[$this->integration][$this->trigger] = [
            'label' => __('Something Happened', 'dollie'),
            'action' => 'my_plugin_event',
            'function' => [$this, 'trigger_listener'],
        ];
        return $triggers;
    }

    public function trigger_listener($data)
    {
        AutomationController::dollie_trigger_handle_trigger([
            'trigger' => $this->trigger,
            'context' => ['data' => $data],
        ]);
    }
}

MyTrigger::get_instance();
```

### 4. Create Action

```php
<?php
namespace Dollie\SDK\Integrations\MyIntegration\Actions;

use Dollie\SDK\Attributes\Action;
use Dollie\SDK\Integrations\AutomateAction;
use Dollie\SDK\Traits\SingletonLoader;

#[Action(
    id: 'my_integration.do_something',
    label: 'Do Something',
    description: 'Performs an action',
    inputSchema: [
        'type' => 'object',
        'required' => ['value'],
        'properties' => [
            'value' => ['type' => 'string', 'description' => 'Value to process']
        ]
    ],
    outputSchema: [
        'type' => 'object',
        'properties' => [
            'success' => ['type' => 'boolean'],
            'message' => ['type' => 'string']
        ]
    ],
    tags: ['actions'],
    since: '1.0.0'
)]
class DoSomething extends AutomateAction
{
    use SingletonLoader;

    public $integration = 'my_integration';
    public $action = 'do_something';

    public function register($actions)
    {
        $actions[$this->integration][$this->action] = [
            'label' => __('Do Something', 'dollie'),
            'action' => $this->action,
            'function' => [$this, 'action_listener'],
        ];
        return $actions;
    }

    public function _action_listener($user_id, $automation_id, $fields, $selected_options)
    {
        $value = $fields['value'] ?? '';

        // Your action logic here
        $result = my_plugin_do_something($value);

        return [
            'success' => $result,
            'message' => 'Action completed successfully',
        ];
    }
}

DoSomething::get_instance();
```

## Generating the Manifest

The SDK uses a manifest-first architecture. Generate the manifest to create JSON definitions of all integrations:

### Using Docker (Recommended)

```bash
make generate
```

### Using PHP Directly

```bash
php scripts/generate-manifest.php
```

This will create:
- `dist/manifest.json` - Complete manifest of all integrations
- `dist/integrations/*.json` - Individual integration manifests

### Manifest Structure

```json
{
    "version": "1.0.0",
    "generated_at": "2025-11-12T10:30:00Z",
    "checksum": "sha256:...",
    "integrations": [
        {
            "id": "WordPress",
            "name": "WordPress",
            "slug": "wordpress",
            "triggers": [...],
            "actions": [...]
        }
    ]
}
```

## Development Commands

```bash
# Install dependencies
make install

# Generate manifest
make generate

# Run tests
make test

# Run all integration fixes
docker compose run --rm php php scripts/final-cleanup.php
```

## Troubleshooting

### Integration Not Loading

1. Ensure your integration class extends `Dollie\SDK\Integrations\Integrations`
2. Verify you're calling `IntegrationsController::register(YourClass::class)`
3. Check that the `#[Integration]` attribute is after the `use` statements

### Trigger Not Firing

1. Verify the WordPress action/filter is being called
2. Check that `AutomationController::dollie_trigger_handle_trigger()` is being called
3. Ensure the trigger is registered via `dollie_trigger_register_trigger` filter

### Action Not Working

1. Confirm the action class extends `AutomateAction`
2. Verify the `_action_listener` method signature matches the base class
3. Check that the action is registered properly

## Support

For issues and questions:
- GitHub: https://github.com/dolliewp/dollie-sdk/issues
- Documentation: https://docs.dollie.com
