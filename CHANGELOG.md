# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [3.1.1] - 2020-12-02
### Added
- Support for PHP 8

## [3.1.0] - 2020-07-09
### Added
- `legacy()` option

## [3.0.0] - 2019-12-01
### Removed
- Support for PHP 7.0 and 7.1

### Fixed
- Update `paragonie/csp-builder` dependency

## [2.0.0] - 2018-08-04
### Added
- PSR-17 support

### Changed
- Moved `middlewares/utils` to dev dependencies

### Removed
- Dropped `report` option. Use [middlewares/reporting-logger](https://github.com/middlewares/reporting-logger) instead.

## [1.0.1] - 2018-01-27
### Fixed
- Fixed variable type errors in `Middlewares\Csp::createFromFile()`, `Middlewares\Csp::createFromData()`.

## [1.0.0] - 2018-01-26
### Added
- Improved testing and added code coverage reporting
- Added tests for PHP 7.2
- New `Middlewares\Csp::createFromFile()` helper to create an instance using a json file
- New `Middlewares\Csp::createFromData()` helper to create an instance using an array with the config.

### Changed
- Upgraded to the final version of PSR-15 `psr/http-server-middleware`

### Fixed
- Updated license year

## [0.6.0] - 2017-11-13
### Changed
- Replaced `http-interop/http-middleware` with  `http-interop/http-server-middleware`.

### Removed
- Removed support for PHP 5.x.

## [0.5.0] - 2017-09-21
### Changed
- Append `.dist` suffix to phpcs.xml and phpunit.xml files
- Changed the configuration of phpcs and php_cs
- Upgraded phpunit to the latest version and improved its config file
- Updated to `http-interop/http-middleware#0.5`

## [0.4.1] - 2017-02-06
### Added
- Insert the legacy headers `X-Webkit-CSP` and `X-Content-Security-Policy`.

## [0.4.0] - 2017-02-05
### Added
- Added the option `report`, to handle CSP errors using `psr/log`.

## [0.3.0] - 2016-12-26
### Changed
- Updated tests
- Updated to `http-interop/http-middleware#0.4`
- Updated `friendsofphp/php-cs-fixer#2.0`

## [0.2.0] - 2016-11-27
### Changed
- Updated to `http-interop/http-middleware#0.3`

## 0.1.0 - 2016-10-09
First version

[3.1.1]: https://github.com/middlewares/csp/compare/v3.1.0...v3.1.1
[3.1.0]: https://github.com/middlewares/csp/compare/v3.0.0...v3.1.0
[3.0.0]: https://github.com/middlewares/csp/compare/v2.0.0...v3.0.0
[2.0.0]: https://github.com/middlewares/csp/compare/v1.0.1...v2.0.0
[1.0.1]: https://github.com/middlewares/csp/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/middlewares/csp/compare/v0.6.0...v1.0.0
[0.6.0]: https://github.com/middlewares/csp/compare/v0.5.0...v0.6.0
[0.5.0]: https://github.com/middlewares/csp/compare/v0.4.1...v0.5.0
[0.4.1]: https://github.com/middlewares/csp/compare/v0.4.0...v0.4.1
[0.4.0]: https://github.com/middlewares/csp/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/middlewares/csp/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/middlewares/csp/compare/v0.1.0...v0.2.0
