# Contributing to Dollie SDK

Thank you for your interest in contributing to the Dollie SDK! This document provides guidelines and instructions for contributing.

## Code of Conduct

Be respectful, constructive, and professional in all interactions.

## How to Contribute

### Reporting Bugs

1. Check if the bug has already been reported in [Issues](https://github.com/dolliewp/dollie-sdk/issues)
2. If not, create a new issue with:
   - Clear title and description
   - Steps to reproduce
   - Expected vs actual behavior
   - PHP version and environment details
   - Relevant code samples or error messages

### Suggesting Features

1. Check existing [Issues](https://github.com/dolliewp/dollie-sdk/issues) and [Pull Requests](https://github.com/dolliewp/dollie-sdk/pulls)
2. Create a new issue with:
   - Clear use case description
   - Proposed solution or API
   - Examples of how it would be used
   - Any alternatives considered

### Contributing Code

#### Prerequisites

- PHP 8.2 or higher
- Composer
- Git
- Familiarity with PHP 8 attributes

#### Setup Development Environment

1. Fork the repository
2. Clone your fork:
```bash
git clone https://github.com/YOUR_USERNAME/dollie-sdk.git
cd dollie-sdk
```

3. Install dependencies:
```bash
composer install
```

4. Create a feature branch:
```bash
git checkout -b feature/your-feature-name
```

#### Making Changes

1. **Write Code**: Follow the coding standards below
2. **Add Tests**: All new features must include tests
3. **Update Documentation**: Update README.md and DEVELOPER_GUIDE.md as needed
4. **Generate Manifest**: Run `composer generate-manifest`
5. **Run Tests**: Ensure all tests pass with `composer test`

#### Coding Standards

**PHP Standards**
- Follow PSR-12 coding style
- Use strict types: `declare(strict_types=1);`
- Use type hints for all parameters and return types
- Add PHPDoc blocks for classes and public methods

**Naming Conventions**
- Classes: PascalCase (`MyClass`)
- Methods: camelCase (`myMethod`)
- Properties: camelCase (`$myProperty`)
- Constants: SCREAMING_SNAKE_CASE (`MY_CONSTANT`)

**Example**
```php
<?php

declare(strict_types=1);

namespace Dollie\SDK\Example;

/**
 * Example Class
 *
 * Demonstrates proper code structure.
 */
class ExampleClass
{
    /**
     * @param string $parameter Description of parameter
     * @return array<string, mixed> Description of return value
     */
    public function exampleMethod(string $parameter): array
    {
        return [
            'key' => $parameter
        ];
    }
}
```

#### Commit Messages

Follow conventional commits format:

```
type(scope): subject

body

footer
```

**Types:**
- `feat`: New feature
- `fix`: Bug fix
- `docs`: Documentation changes
- `style`: Code style changes (formatting, etc.)
- `refactor`: Code refactoring
- `test`: Adding or updating tests
- `chore`: Maintenance tasks

**Examples:**
```
feat(attributes): add support for required fields in schemas

Adds a new 'required' parameter to attribute definitions
to mark fields as mandatory in the manifest.

Closes #123
```

```
fix(generator): handle empty integration directories

Prevents crash when integration has no triggers or actions.
```

#### Testing

**Write Unit Tests**
```php
<?php

namespace Dollie\SDK\Tests\Unit;

use PHPUnit\Framework\TestCase;

class MyFeatureTest extends TestCase
{
    public function test_feature_works_correctly(): void
    {
        // Arrange
        $input = 'test';

        // Act
        $result = myFunction($input);

        // Assert
        $this->assertSame('expected', $result);
    }
}
```

**Run Tests**
```bash
composer test
```

**Check Coverage** (if applicable)
```bash
composer test -- --coverage-html coverage
```

#### Pull Request Process

1. **Update Documentation**: Ensure README, DEVELOPER_GUIDE, and examples are updated
2. **Generate Manifest**: Run `composer generate-manifest` and commit changes
3. **Push Changes**:
```bash
git add .
git commit -m "feat: your feature description"
git push origin feature/your-feature-name
```

4. **Create Pull Request**:
   - Go to [Pull Requests](https://github.com/dolliewp/dollie-sdk/pulls)
   - Click "New Pull Request"
   - Select your fork and branch
   - Fill in the template:
     - Description of changes
     - Related issue numbers
     - Testing performed
     - Screenshots (if applicable)

5. **Code Review**: Address any feedback from maintainers

6. **Merge**: Once approved, a maintainer will merge your PR

### Contributing Integrations

#### New Integration Checklist

- [ ] Integration class with `#[Integration]` attribute
- [ ] At least one trigger or action
- [ ] Comprehensive schemas with descriptions
- [ ] Realistic examples
- [ ] `is_plugin_installed()` method implementation
- [ ] Documentation in examples/
- [ ] Tests for integration logic
- [ ] Manifest generation succeeds

#### Integration Guidelines

1. **Namespace Structure**:
```
Dollie\SDK\Integrations\{IntegrationName}\
├── {IntegrationName}.php
├── Triggers\
└── Actions\
```

2. **Attribute Requirements**:
   - Unique IDs across all integrations
   - Clear, descriptive labels
   - Detailed descriptions
   - Complete schemas
   - Relevant examples
   - Appropriate tags

3. **Schema Best Practices**:
   - Mark required fields
   - Add descriptions for all fields
   - Use appropriate types
   - Include validation constraints
   - Document enums

4. **Example Quality**:
   - Use realistic data
   - Cover common use cases
   - Include edge cases in tests

### Documentation

#### Documentation Standards

- Write clear, concise prose
- Use active voice
- Include code examples
- Add links to related docs
- Keep examples up to date

#### Where to Update

- **README.md**: Overview, quick start, basic usage
- **DEVELOPER_GUIDE.md**: Detailed technical documentation
- **examples/**: Practical, copy-paste ready examples
- **Inline Comments**: Complex logic, non-obvious decisions

### Release Process

Releases are handled by maintainers:

1. Update version in `composer.json`
2. Update CHANGELOG.md
3. Create git tag
4. Push tag to trigger CI/CD
5. GitHub Actions creates release with artifacts

## Project Structure

```
dollie-sdk/
├── .github/workflows/    # CI/CD workflows
├── dist/                 # Generated manifests (committed)
├── examples/             # Example integrations
├── scripts/              # Build scripts
│   ├── generate-manifest.php
│   └── src/              # Generator classes
├── src/                  # SDK source code
│   ├── Attributes/       # Attribute definitions
│   └── Integrations/     # Integration implementations
├── tests/                # Test suite
│   ├── Unit/
│   └── Integration/
├── composer.json
├── phpunit.xml
├── README.md
├── DEVELOPER_GUIDE.md
└── CONTRIBUTING.md
```

## Getting Help

- **Questions**: Open a [Discussion](https://github.com/dolliewp/dollie-sdk/discussions)
- **Bugs**: Open an [Issue](https://github.com/dolliewp/dollie-sdk/issues)
- **Security**: Email security@getdollie.com

## Recognition

Contributors will be recognized in:
- Repository contributors list
- CHANGELOG.md for their contributions
- Release notes

Thank you for contributing to Dollie SDK!
