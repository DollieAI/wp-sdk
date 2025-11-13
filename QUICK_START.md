# Quick Start Guide

Get started with the Dollie SDK in 5 minutes using Docker (no PHP installation required).

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/) installed
- [Docker Compose](https://docs.docker.com/compose/install/) installed
- Make (optional, but recommended)

## 1. Initial Setup

```bash
# Clone or navigate to the project
cd /Users/gabi/projects/dollie-wp/dollie-sdk

# Install dependencies and generate manifest
make setup
```

This will:
- Install Composer dependencies via Docker
- Generate the manifest automatically
- Create `dist/manifest.json` and `dist/integrations/*.json`

## 2. View the Generated Manifest

```bash
# View main manifest
cat dist/manifest.json | jq '.'

# View specific integration
cat dist/integrations/custom-notifier.json | jq '.'

# List all integrations
ls dist/integrations/
```

## 3. Run Tests

```bash
# Run all tests
make test
```

Expected output:
```
PHPUnit 10.5.58 by Sebastian Bergmann and contributors.

.........                                                           9 / 9 (100%)

Time: 00:00.104, Memory: 8.00 MB

OK (9 tests, 27 assertions)
```

## 4. Create Your First Integration

### Step 1: Create Integration Directory

```bash
mkdir -p src/Integrations/MyService/{Triggers,Actions}
```

### Step 2: Create Integration Class

Create `src/Integrations/MyService/MyService.php`:

```php
<?php

declare(strict_types=1);

namespace Dollie\SDK\Integrations\MyService;

use Dollie\SDK\Attributes\Integration;

#[Integration(
    id: 'my_service',
    name: 'My Service',
    slug: 'my-service',
    since: '1.0.0',
    homepage: 'https://myservice.com',
    tags: ['api', 'integration']
)]
class MyService
{
    public function is_plugin_installed(): bool
    {
        return class_exists('MyServicePlugin');
    }
}
```

### Step 3: Create a Trigger

Create `src/Integrations/MyService/Triggers/EventOccurred.php`:

```php
<?php

declare(strict_types=1);

namespace Dollie\SDK\Integrations\MyService\Triggers;

use Dollie\SDK\Attributes\Trigger;

#[Trigger(
    id: 'my_service.event_occurred',
    label: 'Event Occurred',
    description: 'Fires when an event occurs',
    payloadSchema: [
        'type' => 'object',
        'required' => ['event_id'],
        'properties' => [
            'event_id' => [
                'type' => 'integer',
                'description' => 'Event ID'
            ]
        ]
    ],
    examples: [
        ['event_id' => 123]
    ],
    tags: ['events'],
    since: '1.0.0'
)]
class EventOccurred
{
    public string $integration = 'my_service';
    public string $trigger = 'my_service.event_occurred';
}
```

### Step 4: Generate Manifest

```bash
make generate
```

### Step 5: Verify Your Integration

```bash
# Check that your integration appears in the manifest
cat dist/manifest.json | jq '.integrations[] | select(.id == "my_service")'

# View your integration file
cat dist/integrations/my-service.json | jq '.'
```

## 5. Common Commands

```bash
# Install/update dependencies
make install
make update

# Generate manifest
make generate

# Run tests
make test

# Run all CI checks (validate, install, generate, test)
make ci

# Open a shell for debugging
make shell

# Clean generated files
make clean

# View all available commands
make help
```

## 6. Development Workflow

```bash
# 1. Make changes to your integration files
vim src/Integrations/MyService/MyService.php

# 2. Regenerate manifest
make generate

# 3. Check the output
cat dist/integrations/my-service.json | jq '.'

# 4. Run tests
make test

# 5. Validate everything before committing
make ci
```

## 7. Debugging

If something goes wrong:

```bash
# Open a shell in the PHP container
make shell

# Inside the container:
php -v                                    # Check PHP version
composer --version                         # Check Composer version
php scripts/generate-manifest.php         # Run generator manually
vendor/bin/phpunit --testdox             # Run tests with detailed output
ls -la dist/                              # Check generated files
cat dist/manifest.json | jq '.checksum'  # Verify checksum

# Exit the container
exit
```

## 8. What's Next?

- Read the [DEVELOPER_GUIDE.md](DEVELOPER_GUIDE.md) for detailed documentation
- Check [examples/custom-integration/README.md](examples/custom-integration/README.md) for more examples
- See [DOCKER.md](DOCKER.md) for advanced Docker usage
- Read [CONTRIBUTING.md](CONTRIBUTING.md) if you want to contribute

## Troubleshooting

### Issue: Permission denied errors

```bash
# Fix file ownership (macOS/Linux)
sudo chown -R $(whoami):$(whoami) vendor/ dist/
```

### Issue: Manifest not generating

```bash
# Check for PHP errors
make shell
php scripts/generate-manifest.php

# Look for specific error messages
```

### Issue: Tests failing

```bash
# Run tests with verbose output
docker compose run --rm php vendor/bin/phpunit --verbose

# Check specific test
docker compose run --rm php vendor/bin/phpunit tests/Unit/Attributes/IntegrationTest.php
```

### Issue: Docker images not pulling

```bash
# Pull images manually
docker compose pull

# Rebuild images
docker compose build --no-cache
```

## Support

- **Documentation**: See [README.md](README.md) and [DEVELOPER_GUIDE.md](DEVELOPER_GUIDE.md)
- **Examples**: Check [examples/](examples/) directory
- **Issues**: [github.com/dolliewp/dollie-sdk/issues](https://github.com/dolliewp/dollie-sdk/issues)

---

**Congratulations!** 🎉 You now have the Dollie SDK up and running!
