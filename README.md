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

### Storage

#### Log storage

```php
use Monolog\Logger;
use SpiralPackages\Profiler\Storage\LogStorage;

$logStorage = new LogStorage(new Logger('profiler'));
```

#### Null storage

```php
use SpiralPackages\Profiler\Storage\NullStorage;
$logStorage = new NullStorage();
```

#### Custom driver

```php
<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;
use SpiralPackages\Profiler\Converter\ConverterInterface;
use SpiralPackages\Profiler\Converter\NullConverter;

final class MyStorage implements StorageInterface
{
    public function __construct(
        private readonly Client $client,
        private readonly ConverterInterface $converter = new NullConverter(),
    ) {
    }

    public function store(string $appName, array $tags, \DateTimeInterface $date, array $data): void
    {
        $this->client->post('http://example.com/trace', [
            'json' => [
                'appName' => $appName,
                'tags' => $tags,
                'date' => $date->format('Y-m-d H:i:s'),
                'data' => $this->converter->convert($data),
            ],
        ]);
    }
}
```

### Driver

#### XHProf driver

```php
use SpiralPackages\Profiler\Driver\XHProfDriver;
use SpiralPackages\Profiler\DriverFactory;

$driver = new XHProfDriver();
// or
$driver = DriverFactory::createTidyWaysDriver();
```

#### Tideways driver

```php
use SpiralPackages\Profiler\Driver\TidyWaysDriver;
use SpiralPackages\Profiler\DriverFactory;

$driver = new TidyWaysDriver();
// or
$driver = DriverFactory::createTidyWaysDriver();
```

#### UProfiler driver

```php
use SpiralPackages\Profiler\Driver\UProfilerDriver;
use SpiralPackages\Profiler\DriverFactory;

$driver = new UProfilerDriver();
// or
$driver = DriverFactory::createUprofilerDriver();
```

#### Auto detect driver

```php
use SpiralPackages\Profiler\DriverFactory;

$driver = DriverFactory::detect();
```

#### Custom driver

```php
<?php

declare(strict_types=1);

namespace App;

final class MyDriver implements DriverInterface
{

    public function start(array $context = []): void
    {
        // start profiling
    }

    public function end(): array
    {
        // end profiling
        $data = ...;

        return $data;
    }
}
```

### Example of usage

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
            tags: ['http.request', (string) $request->getUri()]
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
