<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Driver;

final class XhprofDriver implements DriverInterface
{
    private const DEFAULT_FLAGS = XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU;

    public function start(array $context = [], int $flags = self::DEFAULT_FLAGS): void
    {
        $options = [];
        if (isset($context[Profiler::IGNORED_FUNCTIONS_KEY])) {
            $options['ignored_functions'] = $context[Profiler::IGNORED_FUNCTIONS_KEY];
        }

        \xhprof_enable($flags, $options);
    }

    public function end(): array
    {
        return \xhprof_disable();
    }
}
