<?php

namespace SpiralPackages\Profiler\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use SpiralPackages\Profiler\Converter\FlatterList;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    public function getTrace(): array
    {
        return \json_decode(\file_get_contents(__DIR__ . '/fixture/trace.json'), true)['data'];
    }

    public function getFlatterList(): FlatterList
    {
        $data = \json_decode(\file_get_contents(__DIR__ . '/fixture/flater_list.json'), true);

        return new FlatterList(
            ['ct', 'wt', 'cpu', 'mu', 'pmu'],
            $data['data'],
            $data['indexed']
        );
    }
}
