# middlewares/csp

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
![Testing][ico-ga]
[![Total Downloads][ico-downloads]][link-downloads]

Middleware to add the [Content-Security-Policy](https://content-security-policy.com/) header to the response using [paragonie/csp-builder](https://github.com/paragonie/csp-builder) library.

## Requirements

* PHP >= 7.2
* A [PSR-7 http library](https://github.com/middlewares/awesome-psr15-middlewares#psr-7-implementations)
* A [PSR-15 middleware dispatcher](https://github.com/middlewares/awesome-psr15-middlewares#dispatcher)

## Installation

This package is installable and autoloadable via Composer as [middlewares/csp](https://packagist.org/packages/middlewares/csp).

```sh
composer require middlewares/csp
```

## Example

```php
use ParagonIE\CSPBuilder\CSPBuilder;

$csp = CSPBuilder::fromFile('/path/to/source.json');

Dispatcher::run([
    new Middlewares\Csp($csp)
]);
```

## Usage

Set the CSP builder to the constructor. See [paragonie/csp-builder](https://github.com/paragonie/csp-builder) for more info. If it's not provided, create a generic one with restrictive directives.

### legacy

To generate legacy CSP headers for old browsers (`X-Content-Security-Policy` and `X-Webkit-CSP`). By default is `true` but you can disabled it:

```php
$middleware = (new Middlewares\Csp($csp))->legacy(false);
```

## Helpers

### createFromFile

Shortcut to create instances using a json file:

```php
Dispatcher::run([
    Middlewares\Csp::createFromFile(__DIR__.'/csp-config.json')
]);
```

### createFromData

Shortcut to create instances using an array with data:

```php
Dispatcher::run([
    Middlewares\Csp::createFromData([
        'script-src' => ['self' => true],
        'object-src' => ['self' => true],
        'frame-ancestors' => ['self' => true],
    ])
]);
```

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/csp.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-ga]: https://github.com/middlewares/csp/workflows/testing/badge.svg
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/csp.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/csp
[link-downloads]: https://packagist.org/packages/middlewares/csp
