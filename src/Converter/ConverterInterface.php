<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Converter;

/**
 * @psalm-import-type TTrace from \SpiralPackages\Profiler\Profiler
 */
interface ConverterInterface
{
    /**
     * @param TTrace $data
     */
    public function convert(array $data): mixed;
}
