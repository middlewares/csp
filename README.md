# middlewares/csp

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![SensioLabs Insight][ico-sensiolabs]][link-sensiolabs]

Middleware to add the [Content-Security-Policy](https://content-security-policy.com/) header to the response using [paragonie/csp-builder](https://github.com/paragonie/csp-builder) library.

## Requirements

* PHP >= 5.6
* A [PSR-7](https://packagist.org/providers/psr/http-message-implementation) http mesage implementation ([Diactoros](https://github.com/zendframework/zend-diactoros), [Guzzle](https://github.com/guzzle/psr7), [Slim](https://github.com/slimphp/Slim), etc...)
* A [PSR-15](https://github.com/http-interop/http-middleware) middleware dispatcher ([Middleman](https://github.com/mindplay-dk/middleman), etc...)

## Installation

This package is installable and autoloadable via Composer as [middlewares/csp](https://packagist.org/packages/middlewares/csp).

```sh
composer require middlewares/csp
```

## Example

```php
use ParagonIE\CSPBuilder\CSPBuilder;

$dispatcher = new Dispatcher([
	new Middlewares\Csp(CSPBuilder::fromFile('/path/to/source.json'))
]);

$response = $dispatcher->dispatch(new Request());
```

## Options

#### `__construct(ParagonIE\CSPBuilder\CSPBuilder $builder = null)`

Set the CSP header builder. See [paragonie/csp-builder](https://github.com/paragonie/csp-builder) for more info. If it's not provided, create a generic one with restrictive directives.

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/csp.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/middlewares/csp/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/middlewares/csp.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/csp.svg?style=flat-square
[ico-sensiolabs]: https://img.shields.io/sensiolabs/i/570e79c8-0170-438f-ba97-72eeaadee868.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/csp
[link-travis]: https://travis-ci.org/middlewares/csp
[link-scrutinizer]: https://scrutinizer-ci.com/g/middlewares/csp
[link-downloads]: https://packagist.org/packages/middlewares/csp
[link-sensiolabs]: https://insight.sensiolabs.com/projects/570e79c8-0170-438f-ba97-72eeaadee868
