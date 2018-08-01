# middlewares/csp

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![SensioLabs Insight][ico-sensiolabs]][link-sensiolabs]

Middleware to add the [Content-Security-Policy](https://content-security-policy.com/) header to the response using [paragonie/csp-builder](https://github.com/paragonie/csp-builder) library. It can also handle the CSP error reports using a [Psr log implementation](https://packagist.org/providers/psr/log-implementation).

## Requirements

* PHP >= 7.0
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

$dispatcher = new Dispatcher([
	new Middlewares\Csp($csp)
]);

$response = $dispatcher->dispatch(new ServerRequest());
```

## Options

#### `__construct(ParagonIE\CSPBuilder\CSPBuilder $builder = null)`

Set the CSP header builder. See [paragonie/csp-builder](https://github.com/paragonie/csp-builder) for more info. If it's not provided, create a generic one with restrictive directives.

#### `report(string $path, Psr\Log\LoggerInterface $log)`

Configure the `report-uri` and the logger used to store the CSP reports send by the browser. Example:

```php
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use ParagonIE\CSPBuilder\CSPBuilder;

//Create the logger
$logger = new Logger('csp');
$logger->pushHandler(new StreamHandler(fopen('/csp-reports.txt', 'r+')));

//Create the csp config
$csp = CSPBuilder::fromFile('/path/to/source.json');

$dispatcher = new Dispatcher([
    (new Middlewares\Csp($csp))->report('/csp-report', $logger)
]);

$response = $dispatcher->dispatch(new ServerRequest());
```

## Helpers

#### `createFromFile(string $path)`

Shortcut to create instances using a json file:

```php
$dispatcher = new Dispatcher([
    Middlewares\Csp::createFromFile(__DIR__.'/csp-config.json')
]);
```

#### `createFromData(array $data)`

Shortcut to create instances using an array with data:

```php
$dispatcher = new Dispatcher([
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
[ico-travis]: https://img.shields.io/travis/middlewares/csp/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/middlewares/csp.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/csp.svg?style=flat-square
[ico-sensiolabs]: https://img.shields.io/sensiolabs/i/570e79c8-0170-438f-ba97-72eeaadee868.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/csp
[link-travis]: https://travis-ci.org/middlewares/csp
[link-scrutinizer]: https://scrutinizer-ci.com/g/middlewares/csp
[link-downloads]: https://packagist.org/packages/middlewares/csp
[link-sensiolabs]: https://insight.sensiolabs.com/projects/570e79c8-0170-438f-ba97-72eeaadee868
