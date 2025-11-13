# Custom Integration Example

This example demonstrates how to create a custom integration using the Dollie SDK.

## Structure

```
MyCustomIntegration/
├── MyCustomIntegration.php    # Main integration class
├── Triggers/
│   └── CustomEventTrigger.php # Example trigger
└── Actions/
    └── CustomAction.php        # Example action
```

## Step 1: Create the Integration Class

The integration class serves as the container for your triggers and actions. It must be decorated with the `#[Integration]` attribute.

```php
<?php

namespace YourNamespace\Integrations\MyCustomIntegration;

use Dollie\SDK\Attributes\Integration;

#[Integration(
    id: 'my_custom',              // Unique ID for your integration
    name: 'My Custom Integration', // Display name
    slug: 'my-custom',             // URL-friendly slug
    since: '1.0.0',                // Version when introduced
    homepage: 'https://example.com', // Optional homepage
    tags: ['custom', 'example']    // Optional categorization tags
)]
class MyCustomIntegration
{
    /**
     * Check if the integration's plugin/service is available
     */
    public function is_plugin_installed(): bool
    {
        // Return true if your plugin/service is active
        return class_exists('YourPluginMainClass');
    }
}
```

## Step 2: Create a Trigger

Triggers fire when specific events occur. They must be decorated with the `#[Trigger]` attribute.

```php
<?php

namespace YourNamespace\Integrations\MyCustomIntegration\Triggers;

use Dollie\SDK\Attributes\Trigger;

#[Trigger(
    id: 'my_custom.event_occurred',
    label: 'Event Occurred',
    description: 'Fires when a custom event occurs in your plugin',
    payloadSchema: [
        'type' => 'object',
        'required' => ['event_id'],
        'properties' => [
            'event_id' => [
                'type' => 'integer',
                'description' => 'Unique ID of the event'
            ],
            'event_data' => [
                'type' => 'object',
                'description' => 'Additional event data'
            ]
        ]
    ],
    examples: [
        [
            'event_id' => 123,
            'event_data' => ['key' => 'value']
        ]
    ],
    tags: ['events'],
    since: '1.0.0'
)]
class CustomEventTrigger
{
    public string $integration = 'my_custom';
    public string $trigger = 'my_custom.event_occurred';

    /**
     * Your trigger implementation
     * Hook into WordPress actions/filters to detect events
     */
    public function __construct()
    {
        add_action('your_plugin_event', [$this, 'trigger_listener'], 10, 2);
    }

    public function trigger_listener($event_id, $event_data): void
    {
        // Fire the trigger with context data
        // This would typically call the AutomationController
    }
}
```

## Step 3: Create an Action

Actions perform operations in response to triggers. They must be decorated with the `#[Action]` attribute.

```php
<?php

namespace YourNamespace\Integrations\MyCustomIntegration\Actions;

use Dollie\SDK\Attributes\Action;

#[Action(
    id: 'my_custom.do_something',
    label: 'Do Something',
    description: 'Performs a custom action in your plugin',
    inputSchema: [
        'type' => 'object',
        'required' => ['parameter1'],
        'properties' => [
            'parameter1' => [
                'type' => 'string',
                'description' => 'Required parameter'
            ],
            'parameter2' => [
                'type' => 'integer',
                'description' => 'Optional parameter',
                'default' => 0
            ]
        ]
    ],
    outputSchema: [
        'type' => 'object',
        'properties' => [
            'success' => [
                'type' => 'boolean'
            ],
            'message' => [
                'type' => 'string'
            ]
        ]
    ],
    examples: [
        [
            'parameter1' => 'example value',
            'parameter2' => 42
        ]
    ],
    tags: ['actions'],
    since: '1.0.0'
)]
class CustomAction
{
    public string $integration = 'my_custom';
    public string $action = 'my_custom.do_something';

    /**
     * Execute the action
     *
     * @param int $user_id The user performing the action
     * @param int $automation_id The automation ID
     * @param array $fields Field definitions
     * @param array $selected_options The actual parameter values
     * @return array Response with success status
     */
    public function _action_listener(int $user_id, int $automation_id, array $fields, array $selected_options): array
    {
        // Validate required parameters
        if (empty($selected_options['parameter1'])) {
            return [
                'success' => false,
                'message' => 'Parameter1 is required'
            ];
        }

        // Perform your action logic here
        $result = $this->doSomething(
            $selected_options['parameter1'],
            $selected_options['parameter2'] ?? 0
        );

        return [
            'success' => true,
            'message' => 'Action completed successfully',
            'result' => $result
        ];
    }

    private function doSomething(string $param1, int $param2)
    {
        // Your implementation
        return ['data' => 'result'];
    }
}
```

## Step 4: Register Your Integration

In your plugin's main file or initialization code:

```php
<?php

// Ensure Dollie SDK is loaded
if (!class_exists('Dollie\\SDK\\Attributes\\Integration')) {
    return;
}

// Autoload your integration classes
require_once __DIR__ . '/integrations/MyCustomIntegration/MyCustomIntegration.php';
require_once __DIR__ . '/integrations/MyCustomIntegration/Triggers/CustomEventTrigger.php';
require_once __DIR__ . '/integrations/MyCustomIntegration/Actions/CustomAction.php';

// Initialize your triggers and actions
\YourNamespace\Integrations\MyCustomIntegration\Triggers\CustomEventTrigger::get_instance();
\YourNamespace\Integrations\MyCustomIntegration\Actions\CustomAction::get_instance();
```

## Step 5: Generate Manifest

After creating your integration, regenerate the manifest:

```bash
composer generate-manifest
```

This will scan your code and generate:
- `dist/manifest.json` - Complete manifest with your integration
- `dist/integrations/my-custom.json` - Individual integration file

## Schema Guidelines

### Payload Schema (for Triggers)

Define the structure of data that your trigger will provide:

```php
payloadSchema: [
    'type' => 'object',
    'required' => ['field1'],
    'properties' => [
        'field1' => ['type' => 'string'],
        'field2' => ['type' => 'integer'],
        'field3' => [
            'type' => 'string',
            'enum' => ['option1', 'option2']
        ]
    ]
]
```

### Input/Output Schema (for Actions)

Define what parameters your action accepts and what it returns:

```php
inputSchema: [
    'type' => 'object',
    'required' => ['required_field'],
    'properties' => [
        'required_field' => [
            'type' => 'string',
            'minLength' => 1,
            'maxLength' => 255
        ],
        'optional_field' => [
            'type' => 'integer',
            'minimum' => 0,
            'maximum' => 100,
            'default' => 50
        ]
    ]
]
```

## Best Practices

1. **Use descriptive IDs**: Format as `integration_id.entity.action` (e.g., `my_custom.user.created`)
2. **Provide examples**: Include realistic examples for documentation
3. **Tag appropriately**: Use tags for categorization and filtering
4. **Version carefully**: Use semantic versioning for `since` fields
5. **Validate inputs**: Always validate action inputs before processing
6. **Handle errors**: Return descriptive error messages in action responses
7. **Document schemas**: Provide clear descriptions for all schema fields

## Testing

After creating your integration:

1. Generate the manifest: `composer generate-manifest`
2. Check `dist/integrations/my-custom.json` for your integration data
3. Verify all IDs are unique
4. Test trigger firing in your WordPress environment
5. Test action execution with sample data

## Resources

- [Main README](../../README.md)
- [Dollie Documentation](https://docs.getdollie.com)
- [JSON Schema Reference](https://json-schema.org/understanding-json-schema/)
