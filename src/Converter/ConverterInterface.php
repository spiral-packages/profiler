<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Converter;

interface ConverterInterface
{
    public function convert(array $data): mixed;
}
