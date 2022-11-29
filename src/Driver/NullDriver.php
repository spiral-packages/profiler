<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Driver;

final class NullDriver implements DriverInterface
{

    public function start(array $context = []): void
    {

    }

    public function end(): array
    {

    }
}
