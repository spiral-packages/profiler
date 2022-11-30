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
            'wt' => 58705,
            'cpu' => 58703,
            'mu' => 862760,
            'pmu' => 834608,
            'parents' => [
                null
            ]
        ], $trace['main()']);

        $this->assertSame([
            'ct' => 1,
            'wt' => 58679,
            'cpu' => 58679,
            'mu' => 860480,
            'pmu' => 834608,
            'parents' => [
                'Spiral\Http\Pipeline::Spiral\Http\{closure}'
            ]
        ], $trace['Spiral\\Router\\Router::handle']);
    }
}
