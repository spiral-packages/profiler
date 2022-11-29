<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Driver;

use SpiralPackages\Profiler\Profiler;

final class TidyWaysDriver implements DriverInterface
{
    private const DEFAULT_FLAGS = TIDEWAYS_XHPROF_FLAGS_MEMORY | TIDEWAYS_XHPROF_FLAGS_CPU;

    public function start(array $context = [], int $flags = self::DEFAULT_FLAGS): void
    {
        $options = [];
        if (isset($context[Profiler::IGNORED_FUNCTIONS_KEY])) {
            $options['ignored_functions'] = $context[Profiler::IGNORED_FUNCTIONS_KEY];
        }

        \tideways_xhprof_enable($flags, $options);
    }

    public function end(): array
    {
        return \tideways_xhprof_disable();
    }
}
