<?php

namespace SpiralPackages\Profiler;

use SpiralPackages\Profiler\Driver\DriverInterface;
use SpiralPackages\Profiler\Storage\StorageInterface;

/**
 * @psalm-type TTrace = array<non-empty-string, array{
 *     ct: int,
 *     wt: int,
 *     cpu: int,
 *     mu: int,
 *     pmu: int
 * }>
 */
final class Profiler
{
    public const IGNORED_FUNCTIONS_KEY = 'ignored_functions';

    /**\
     * @param non-empty-string $appName
     * @param non-empty-string[] $tags
     */
    public function __construct(
        private readonly StorageInterface $storage,
        private readonly DriverInterface $driver,
        private readonly string $appName,
        private readonly array $tags = [],
    ) {
    }

    /**
     * Start application profiling.
     *
     * @param non-empty-string[] $ignoredFunctions
     */
    public function start(array $ignoredFunctions = []): void
    {
        $ignoredFunctions = \array_merge(
            $ignoredFunctions,
            ['SpiralPackages\Profiler\Profiler::end']
        );

        $this->driver->start([
            self::IGNORED_FUNCTIONS_KEY => $ignoredFunctions,
        ]);
    }

    /**
     * Finish application profiling and send trace to the storage.
     *
     * @return TTrace
     */
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
