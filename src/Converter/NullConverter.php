<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Converter;

final class NullConverter implements ConverterInterface
{
    public function convert(array $data): array
    {
        return $data;
    }
}
