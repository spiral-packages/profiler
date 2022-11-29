<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Driver;

final class UprofilerDriver implements DriverInterface
{
    private const DEFAULT_FLAGS = UPROFILER_FLAGS_CPU | UPROFILER_FLAGS_MEMORY;

    public function start(array $context = [], int $flags = self::DEFAULT_FLAGS): void
    {
        $options = [];
        if (isset($context[Profiler::IGNORED_FUNCTIONS_KEY])) {
            $options['ignored_functions'] = $context[Profiler::IGNORED_FUNCTIONS_KEY];
        }

        \uprofiler_enable($flags, $options);
    }

    public function end(): array
    {
        return \uprofiler_disable();
    }
}
