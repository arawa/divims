# BigBlueButton API for PHP

![Home Image](https://raw.githubusercontent.com/wiki/bigbluebutton/bigbluebutton-api-php/images/header.png)
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php.svg?type=shield)](https://app.fossa.com/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php?ref=badge_shield)

The official and easy to use **BigBlueButton API for PHP**, makes easy for developers to use [BigBlueButton][bbb] API for **PHP 7.4+**.

![Packagist](https://img.shields.io/packagist/v/bigbluebutton/bigbluebutton-api-php.svg?label=release)
![PHP from Travis config](https://img.shields.io/travis/php-v/bigbluebutton/bigbluebutton-api-php.svg)
[![Downloads](https://img.shields.io/packagist/dt/bigbluebutton/bigbluebutton-api-php.svg?style=flat-square)](https://packagist.org/packages/bigbluebutton/bigbluebutton-api-php)

[![Build Status](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/build.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/bigbluebutton/bigbluebutton-api-php/?branch=master)

[![@bigbluebutton on Twitter](https://img.shields.io/badge/twitter-%40bigbluebutton-blue.svg?style=flat)](https://twitter.com/bigbluebutton)
![Website](https://img.shields.io/website-up-down-green-red/http/bigbluebutton.org.svg?label=BigBlueButton.org)

[![PHP 7.4](https://img.shields.io/badge/php-7.4-f33.svg?style=flat-square)](https://www.php.net/supported-versions.php)
[![PHP 8.0](https://img.shields.io/badge/php-8.0-f93.svg?style=flat-square)](https://www.php.net/supported-versions.php)
[![PHP 8.1](https://img.shields.io/badge/php-8.1-9c9.svg?style=flat-square)](https://www.php.net/supported-versions.php)
[![PHP 8.2](https://img.shields.io/badge/php-8.2-9c9.svg?style=flat-square)](https://www.php.net/supported-versions.php)

## Installation and usage

The [wiki] contains all the documentation related to the PHP library. We have also written a samples to show a full
install and usage example.

## Submitting bugs and feature requests

Bugs and feature request are tracked on [GitHub](https://github.com/bigbluebutton/bigbluebutton-api-php/issues)

## Contributing guidelines
### Code Quality 1: Style

Make sure the code style configuration is applied by running PHPCS-Fixer.

```bash
# using an alias
$ composer cs-fix
```

### Code Quality 2: Static code analysis
PHPStan shall be used for static code analysis by running the command below:

```bash
# using an alias
$ composer code-check

# or the same w/o alias
$ ./vendor/bin/phpstan analyse
```

### Code Quality 3: Tests

For every implemented feature add unit tests and check all is green by running the command below.

```bash
# using an alias
$ composer code-test

# or the same w/o alias
./vendor/bin/phpunit
```

To run a single test

```bash
# using an alias
$ composer code-test -- --filter BigBlueButtonTest::testApiVersion

# or the same w/o alias
./vendor/bin/phpunit --filter BigBlueButtonTest::testApiVersion
```
A code-coverage report will be created along with the tests. This report will be stored in:
````
./var/coverage/
````
In case of trouble with the creation of the code-coverage report (e.g. local environment does not fulfill requirements) 
the creation can be skipped with:
```bash
# using an alias
$ composer code-test -- --no-coverage
```

**Remark:**

Some test will connect to an existing BBB-server, which is specified in the `.env`-file. You 
can specify your own BBB-server by copy that file into the same folder and name it `.env.local`.
Exchange the credentials `BBB_SERVER_BASE_URL` and `BBB_SECRET` to your server's values.
Since this new file (`.env.local`) takes precedence over the main file (`.env`), you will now test 
with your own server.

[bbb]: http://bigbluebutton.org
[composer]: https://getcomposer.org
[INSTALL]: samples/README.md
[wiki]: https://github.com/bigbluebutton/bigbluebutton-api-php/wiki

## License
[![FOSSA Status](https://app.fossa.com/api/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php.svg?type=large)](https://app.fossa.com/projects/git%2Bgithub.com%2Fbigbluebutton%2Fbigbluebutton-api-php?ref=badge_large)