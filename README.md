[![License](https://img.shields.io/github/license/imponeer/smarty-foreachq.svg)](LICENSE) [![GitHub release](https://img.shields.io/github/release/imponeer/smarty-foreachq.svg)](https://github.com/imponeer/smarty-foreachq/releases) [![PHP](https://img.shields.io/packagist/php-v/imponeer/smarty-foreachq.svg)](http://php.net) [![Packagist](https://img.shields.io/packagist/dm/imponeer/smarty-foreachq.svg)](https://packagist.org/packages/imponeer/smarty-foreachq) [![Smarty version requirement](https://img.shields.io/packagist/dependency-v/imponeer/smarty-foreachq/smarty%2Fsmarty)](https://smarty-php.github.io)

# Smarty ForeachQ

> Backward compatibility foreach implementation for legacy XOOPS and ImpressCMS templates

Rewritten (due that original use [GPLv2+](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html) license) [Smarty](https://smarty.net) foreach variant that was invented for use in [XOOPS](https://xoops.org), but nowadays used in some other PHP based CMS'es (like [ImpressCMS](https://impresscms.org)!).

See, [original version of this smarty plugin in Xoops](https://github.com/XOOPS/XoopsCore25/blob/v2.5.8/htdocs/class/smarty/xoops_plugins/compiler.foreachq.php) to see more accurate description why this plugin exists.

## Installation

To install and use this package, we recommend to use [Composer](https://getcomposer.org):

```bash
composer require imponeer/smarty-foreachq
```

Otherwise, you need to include manually files from `src/` directory.

## Setup

### Basic Setup

To register the ForeachQ extension with Smarty, add the extension class to your Smarty instance:

```php
// Create a Smarty instance
$smarty = new \Smarty\Smarty();

// Register the ForeachQ extension
$smarty->addExtension(new \Imponeer\Smarty\Extensions\ForeachQ\ForeachQExtension());
```

### Using with Symfony Container

To integrate with Symfony, you can leverage autowiring, which is the recommended approach for modern Symfony applications:

```yaml
# config/services.yaml
services:
    # Enable autowiring and autoconfiguration
    _defaults:
        autowire: true
        autoconfigure: true

    # Register your application's services
    App\:
        resource: '../src/*'
        exclude: '../src/{DependencyInjection,Entity,Tests,Kernel.php}'

    # Configure Smarty with the extension
    # The ForeachQExtension will be autowired automatically
    \Smarty\Smarty:
        calls:
            - [addExtension, ['@Imponeer\Smarty\Extensions\ForeachQ\ForeachQExtension']]
```

Then in your application code, you can simply retrieve the pre-configured Smarty instance:

```php
// Get the Smarty instance with the ForeachQ extension already added
$smarty = $container->get(\Smarty\Smarty::class);
```

### Using with PHP-DI

With PHP-DI container, you can take advantage of autowiring for a very simple configuration:

```php
use function DI\create;
use function DI\get;

return [
    // Configure Smarty with the extension
    \Smarty\Smarty::class => create()
        ->method('addExtension', get(\Imponeer\Smarty\Extensions\ForeachQ\ForeachQExtension::class))
];
```

Then in your application code, you can retrieve the Smarty instance:

```php
// Get the configured Smarty instance
$smarty = $container->get(\Smarty\Smarty::class);
```

### Using with League Container

If you're using League Container, you can register the extension like this:

```php
// Create the container
$container = new \League\Container\Container();

// Register Smarty with the ForeachQ extension
$container->add(\Smarty\Smarty::class, function() {
    $smarty = new \Smarty\Smarty();

    // Configure Smarty...

    // Create and add the ForeachQ extension
    $extension = new \Imponeer\Smarty\Extensions\ForeachQ\ForeachQExtension();
    $smarty->addExtension($extension);

    return $smarty;
});
```

Then in your application code, you can retrieve the Smarty instance:

```php
// Get the configured Smarty instance
$smarty = $container->get(\Smarty\Smarty::class);
```

## Usage

**Simple iteration:**
```smarty
{foreachq from=$users item=user}
    <p>User: {$user.name}</p>
{/foreachq}
```

**With key and item:**
```smarty
{foreachq from=$data key=index item=value}
    <p>{$index}: {$value}</p>
{/foreachq}
```

**Nested loops:**
```smarty
{foreachq from=$categories item=category}
    <h2>{$category.name}</h2>
    {foreachq from=$category.items item=item}
        <p>- {$item.title}</p>
    {/foreachq}
{/foreachq}
```

## Development

### Code Quality Tools

This project uses several tools to ensure code quality:

- **PHPUnit** - For unit testing
```bash
composer test
```

- **PHP CodeSniffer** - For coding standards (PSR-12)
```bash
composer phpcs    # Check code style
composer phpcbf   # Fix code style issues automatically
```

- **PHPStan** - For static analysis
```bash
composer phpstan
```

## Documentation

API documentation is automatically generated and available in the project's wiki. For more detailed information about the classes and methods, please refer to the [project wiki](https://github.com/imponeer/smarty-foreachq/wiki).

## Contributing

Contributions are welcome! Here's how you can contribute:

1. Fork the repository
2. Create a feature branch: `git checkout -b feature-name`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin feature-name`
5. Submit a pull request

Please make sure your code follows the PSR-12 coding standard and include tests for any new features or bug fixes.

If you find a bug or have a feature request, please create an issue in the [issue tracker](https://github.com/imponeer/smarty-foreachq/issues).
