<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Tests\Converter;

use SpiralPackages\Profiler\Tests\TestCase;

class FlatterListTest extends TestCase
{
    public function testCallGraph(): void
    {
        $this->markTestSkipped('TODO: write this test');

        $list = $this->getFlatterList();

        $graph = $list->callGraph();
    }
}
