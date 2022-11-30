<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Tests;

use Mockery as m;
use SpiralPackages\Profiler\Driver\DriverInterface;
use SpiralPackages\Profiler\Profiler;
use SpiralPackages\Profiler\Storage\StorageInterface;

class ProfilerTest extends TestCase
{
    private Profiler $profiler;
    private m\MockInterface|StorageInterface $storage;
    private m\MockInterface|DriverInterface $driver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->profiler = new Profiler(
            $this->storage = m::mock(StorageInterface::class),
            $this->driver = m::mock(DriverInterface::class),
            'my-super-app',
            ['tag1', 'tag2'],
        );
    }

    public function testStart(): void
    {
        $this->driver->shouldReceive('start')->once()->with([
            Profiler::IGNORED_FUNCTIONS_KEY => [],
        ]);

        $this->profiler->start();
    }

    public function testStartWithIgnoredFunctions(): void
    {
        $this->driver->shouldReceive('start')->once()->with([
            Profiler::IGNORED_FUNCTIONS_KEY => ['array_map', 'array_keys'],
        ]);

        $this->profiler->start(['array_map', 'array_keys']);
    }

    public function testEnd(): void
    {
        $this->driver->shouldReceive('start')->once();
        $this->driver->shouldReceive('end')->once()->andReturn(
            $trace = [
                'main()' => [
                    'ct' => 1.0,
                    'wt' => 2.0,
                    'cpu' => 3.0,
                    'mu' => 4.0,
                    'pmu' => 5.0,
                ],
            ]
        );

        $this->storage->shouldReceive('store')->once()->with(
            'my-super-app',
            ['tag1', 'tag2'],
            m::type(\DateTimeImmutable::class),
            $trace
        );

        $this->profiler->start();
        $this->assertSame($trace, $this->profiler->end());
    }
}
