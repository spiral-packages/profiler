<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Storage;

final class CompositeStorage implements StorageInterface
{
    /** @var StorageInterface[] */
    private readonly array $storages;

    public function __construct(
        StorageInterface ...$storages
    ) {
        $this->storages = $storages;
    }

    public function store(string $appName, array $tags, \DateTimeInterface $date, array $data): void
    {
        foreach ($this->storages as $storage) {
            $storage->store($appName, $tags, $date, $data);
        }
    }
}
