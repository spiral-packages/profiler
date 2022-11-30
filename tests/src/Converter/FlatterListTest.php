<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Tests\Converter;

use SpiralPackages\Profiler\Tests\TestCase;

class FlatterListTest extends TestCase
{
    public function testCreateCallGraph(): void
    {
        $graph = $this->getFlatterList()->callGraph('wt');

        $p = 0;
        $this->assertSame('', $graph);
    }
}
