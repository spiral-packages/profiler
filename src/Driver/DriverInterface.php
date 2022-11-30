<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Driver;

/**
 * @psalm-import-type TTrace from \SpiralPackages\Profiler\Profiler
 */
interface DriverInterface
{
    public function start(array $context = []): void;

    /**
     * @return TTrace
     */
    public function end(): array;
}
