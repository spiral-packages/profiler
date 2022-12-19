<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Driver;

use SpiralPackages\Profiler\Profiler;

final class XhprofDriver implements DriverInterface
{
    /** @psalm-suppress UndefinedConstant */
    private const DEFAULT_FLAGS = XHPROF_FLAGS_MEMORY | XHPROF_FLAGS_CPU | XHPROF_FLAGS_NO_BUILTINS;

    public function start(array $context = [], int $flags = self::DEFAULT_FLAGS): void
    {
        $options = [
            'ignored_functions' => ['xhprof_disable', 'SpiralPackages\Profiler\Driver\XhprofDriver::end'],
        ];

        if (isset($context[Profiler::IGNORED_FUNCTIONS_KEY])) {
            $options['ignored_functions'] = \array_merge(
                $options['ignored_functions'],
                $context[Profiler::IGNORED_FUNCTIONS_KEY]
            );
        }

        /** @psalm-suppress UndefinedFunction */
        \xhprof_enable($flags, $options);
    }

    public function end(): array
    {
        /** @psalm-suppress UndefinedFunction */
        return \xhprof_disable();
    }
}
