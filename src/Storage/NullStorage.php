<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Storage;

final class NullStorage implements StorageInterface
{

    public function store(string $appName, array $tags, \DateTimeInterface $date, array $data): void
    {

    }
}
