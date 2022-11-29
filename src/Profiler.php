<?php

namespace SpiralPackages\Profiler;

use SpiralPackages\Profiler\Driver\DriverInterface;
use SpiralPackages\Profiler\Storage\StorageInterface;

final class Profiler
{
    public const IGNORED_FUNCTIONS_KEY = 'ignored_functions';

    public function __construct(
        private readonly StorageInterface $storage,
        private readonly DriverInterface $driver,
        private readonly string $appName,
        private readonly array $tags = [],
    ) {
    }

    /**
     * @param non-empty-string[] $ignoredFunctions
     */
    public function start(array $ignoredFunctions = []): void
    {
        \register_shutdown_function([$this, 'end']);

        $this->driver->start([
            self::IGNORED_FUNCTIONS_KEY => $ignoredFunctions,
        ]);
    }

    public function end(): array
    {
        $result = $this->driver->end();
        $this->storage->store(
            appName: $this->appName,
            tags: $this->tags,
            date: new \DateTimeImmutable(),
            data: $result
        );

        return $result;
    }
}
