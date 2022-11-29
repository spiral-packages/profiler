# A system-wide performance monitoring system profiler.

[![PHP Version Require](https://poser.pugx.org/spiral-packages/xhprof-client/require/php)](https://packagist.org/packages/spiral-packages/xhprof-client)
[![Latest Stable Version](https://poser.pugx.org/spiral-packages/xhprof-client/v/stable)](https://packagist.org/packages/spiral-packages/xhprof-client)
[![phpunit](https://github.com/spiral-packages/xhprof-client/actions/workflows/phpunit.yml/badge.svg)](https://github.com/spiral-packages/xhprof-client/actions)
[![psalm](https://github.com/spiral-packages/xhprof-client/actions/workflows/psalm.yml/badge.svg)](https://github.com/spiral-packages/xhprof-client/actions)
[![Codecov](https://codecov.io/gh/spiral-packages/xhprof-client/branch/master/graph/badge.svg)](https://codecov.io/gh/spiral-packages/xhprof-client/)
[![Total Downloads](https://poser.pugx.org/spiral-packages/xhprof-client/downloads)](https://packagist.org/spiral-packages/xhprof-client/phpunit)
<a href="https://discord.gg/8bZsjYhVVk"><img src="https://img.shields.io/badge/discord-chat-magenta.svg"></a>

Profiler is a system-wide performance monitoring system, that is built on top
of [XHProf](http://pecl.php.net/package/xhprof) or its forks ([Uprofiler](https://github.com/FriendsOfPHP/uprofiler)
or [Tideways](https://github.com/tideways/php-profiler-extension)).

Profiler continually gathers function-level profiler data from production tier by running a sample of page requests
under XHProf.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+

## Installation

You can install the package via composer:

```bash
composer require spiral-packages/xhprof-client
```

## Testing

```bash
composer test
```

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [butschster](https://github.com/spiral-packages)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
