<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Tests\Storage;

use SpiralPackages\Profiler\Serializer\StandartSerializer;
use SpiralPackages\Profiler\Storage\FileStorage;
use SpiralPackages\Profiler\Tests\TestCase;

/**
 * @coversDefaultClass \SpiralPackages\Profiler\Storage\FileStorage
 */
final class FileStorageTest extends TestCase
{
    /**
     * @covers ::store
     */
    public function testStoreDefault(): void
    {
        $fs = new FileStorage();
        $fs->store(
            'myapp',
            [],
            new \DateTimeImmutable('2024-05-13T00:00:00+00:00'),
            ['foo' => 'bar']
        );
        self::assertFileExists('/tmp/1715558400.myapp.xhprof');
    }

    /**
     * @covers ::store
     */
    public function testStoreWithConstructorArguments(): void
    {
        $fs = new FileStorage(
            serializer: new StandartSerializer(),
            dir: '/tmp/',
            extension: 'dump',
        );
        $fs->store(
            'myapp',
            [],
            new \DateTimeImmutable('2024-05-13T00:00:00+00:00'),
            ['foo' => 'bar']
        );
        self::assertFileExists('/tmp/1715558400.myapp.dump');
    }
}
