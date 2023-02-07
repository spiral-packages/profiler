# A system-wide performance monitoring system profiler.

[![PHP Version Require](https://poser.pugx.org/spiral-packages/profiler/require/php)](https://packagist.org/packages/spiral-packages/profiler)
[![Latest Stable Version](https://poser.pugx.org/spiral-packages/profiler/v/stable)](https://packagist.org/packages/spiral-packages/profiler)
[![phpunit](https://github.com/spiral-packages/profiler/actions/workflows/phpunit.yml/badge.svg)](https://github.com/spiral-packages/profiler/actions)
[![psalm](https://github.com/spiral-packages/profiler/actions/workflows/psalm.yml/badge.svg)](https://github.com/spiral-packages/profiler/actions)
[![Codecov](https://codecov.io/gh/spiral-packages/profiler/branch/master/graph/badge.svg)](https://codecov.io/gh/spiral-packages/profiler/)
[![Total Downloads](https://poser.pugx.org/spiral-packages/profiler/downloads)](https://packagist.org/spiral-packages/profiler/phpunit)

Profiler is a system-wide performance monitoring system, that is built on top
of [XHProf](http://pecl.php.net/package/xhprof) or its forks ([Uprofiler](https://github.com/FriendsOfPHP/uprofiler)
or [Tideways](https://github.com/tideways/php-profiler-extension)).

Profiler continually gathers function-level profiler data from production tier by running a sample of page requests
under XHProf.

## Requirements

Make sure that your server is configured with following PHP version and extensions:

- PHP 8.1+
- [XHProf](http://pecl.php.net/package/xhprof) ext or its forks ([Uprofiler](https://github.com/FriendsOfPHP/uprofiler)
  , [Tideways](https://github.com/tideways/php-profiler-extension)).

## Documentation, Installation, and Usage Instructions

See the [documentation](https://spiral.dev/docs/basics-debug#xhprof) for detailed installation and usage instructions.

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
