# Change Log
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/) 
and this project adheres to [Semantic Versioning](http://semver.org/).

## UNRELEASED

### Changed

* Append `.dist` suffix to phpcs.xml and phpunit.xml files
* Changed the configuration of phpcs and php_cs
* Upgraded phpunit to the latest version and improved its config file
* Updated to `http-interop/http-middleware#0.5`

## [0.4.1] - 2017-02-06

### Added

* Insert the legacy headers `X-Webkit-CSP` and `X-Content-Security-Policy`.

## [0.4.0] - 2017-02-05

* Added the option `report`, to handle CSP errors using `psr/log`.

## [0.3.0] - 2016-12-26

### Changed

* Updated tests
* Updated to `http-interop/http-middleware#0.4`
* Updated `friendsofphp/php-cs-fixer#2.0`

## [0.2.0] - 2016-11-27

### Changed

* Updated to `http-interop/http-middleware#0.3`

## 0.1.0 - 2016-10-09

First version

[0.4.1]: https://github.com/middlewares/csp/compare/v0.4.0...v0.4.1
[0.4.0]: https://github.com/middlewares/csp/compare/v0.3.0...v0.4.0
[0.3.0]: https://github.com/middlewares/csp/compare/v0.2.0...v0.3.0
[0.2.0]: https://github.com/middlewares/csp/compare/v0.1.0...v0.2.0
