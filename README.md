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

## Installation

You can install the package via composer:

```bash
composer require spiral-packages/profiler
```

## Usage

```php
use SpiralPackages\Profiler\DriverFactory;
use SpiralPackages\Profiler\Profiler;
use SpiralPackages\Profiler\Storage\LogStorage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Log\LoggerInterface;

class ProfilerMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $profiler = new Profiler(
            storage: new LogStorage($this->logger),
            driver: DriverFactory::detect(),
            appName: 'my-app',
            tags: ['request']
        );

        $profiler->start();

        try {
            return $handler->handle($request);
        } finally {
            $profiler->end();
        }
    }
}
```

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [butschster](https://github.com/spiral-packages)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
