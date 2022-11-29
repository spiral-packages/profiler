<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Storage;

interface StorageInterface
{
    /**
     * @param non-empty-string $appName
     * @param non-empty-string[] $tags
     * @param \DateTimeInterface $date
     * @param array $data
     */
    public function store(string $appName, array $tags, \DateTimeInterface $date, array $data): void;
}
