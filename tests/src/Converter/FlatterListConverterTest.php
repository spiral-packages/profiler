<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Tests\Converter;

use SpiralPackages\Profiler\Converter\FlatterListConverter;
use SpiralPackages\Profiler\Tests\TestCase;

final class FlatterListConverterTest extends TestCase
{
    public function testConvert(): void
    {
        $converter = new FlatterListConverter();

        $trace = $converter->convert($this->getTrace())->getData();
        $this->assertSame([
            'ct' => 1,
            'wt' => 121942,
            'cpu' => 121982,
            'mu' => 925088,
            'pmu' => 836240,
            'parents' => [
                null
            ]
        ], $trace['main()']);

        $this->assertSame([
            'ct' => 1,
            'wt' => 121899,
            'cpu' => 121943,
            'mu' => 924720,
            'pmu' => 836240,
            'parents' => [
                'Spiral\Http\Pipeline::Spiral\Http\{closure}'
            ]
        ], $trace['Spiral\\Router\\Router::handle']);
    }
}
