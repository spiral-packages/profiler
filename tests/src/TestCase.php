<?php

namespace SpiralPackages\Profiler\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use SpiralPackages\Profiler\Converter\FlatterList\FlatterList;

class TestCase extends \PHPUnit\Framework\TestCase
{
    use MockeryPHPUnitIntegration;

    public function getTrace(): array
    {
        return \json_decode(\file_get_contents(__DIR__ . '/fixture/trace.json'), true)['data'];
    }

    public function getFlatterList(string $version = 'v1'): FlatterList
    {
        $data = (array) \json_decode(\file_get_contents(__DIR__ . "/fixture/flater_list.{$version}.json"), true);

        return new FlatterList(
            ['ct', 'wt', 'cpu', 'mu', 'pmu'],
            $data
        );
    }
}
