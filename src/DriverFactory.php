<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler;

use SpiralPackages\Profiler\Driver\NullDriver;
use SpiralPackages\Profiler\Exception\ProfilerNotFoundException;
use SpiralPackages\Profiler\Driver\DriverInterface;
use SpiralPackages\Profiler\Driver\TidyWaysDriver;
use SpiralPackages\Profiler\Driver\UprofilerDriver;
use SpiralPackages\Profiler\Driver\XhprofDriver;

final class DriverFactory
{
    public static function detect(): DriverInterface
    {
        if (\function_exists('xhprof_enable')) {
            return self::createXhrofDriver();
        }

        if (\function_exists('tideways_xhprof_enable')) {
            return self::createTidyWaysDriver();
        }

        if (\function_exists('uprofiler_enable')) {
            return self::createUprofilerDriver();
        }

        throw new ProfilerNotFoundException();
    }

    public static function createXhrofDriver(): XhprofDriver
    {
        return new XhprofDriver();
    }

    public static function createTidyWaysDriver(): TidyWaysDriver
    {
        return new TidyWaysDriver();
    }

    public static function createUprofilerDriver(): UprofilerDriver
    {
        return new UprofilerDriver();
    }

    public static function createNullDriver(): NullDriver
    {
        return new NullDriver();
    }
}
