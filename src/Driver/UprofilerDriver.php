<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Driver;

use SpiralPackages\Profiler\Profiler;

final class UprofilerDriver implements DriverInterface
{
    /** @psalm-suppress UndefinedConstant */
    private const DEFAULT_FLAGS = UPROFILER_FLAGS_CPU | UPROFILER_FLAGS_MEMORY;

    public function start(array $context = [], int $flags = self::DEFAULT_FLAGS): void
    {
        $options = [
            'ignored_functions' => ['uprofiler_disable', 'SpiralPackages\Profiler\Driver\UprofilerDriver::end'],
        ];

        if (isset($context[Profiler::IGNORED_FUNCTIONS_KEY])) {
            $options['ignored_functions'] = \array_merge(
                $options['ignored_functions'],
                $context[Profiler::IGNORED_FUNCTIONS_KEY]
            );
        }

        /** @psalm-suppress UndefinedFunction */
        \uprofiler_enable($flags, $options);
    }

    public function end(): array
    {
        /** @psalm-suppress UndefinedFunction */
        return \uprofiler_disable();
    }
}
