<?php

declare(strict_types=1);
namespace SpiralPackages\Profiler\Storage;

use SpiralPackages\Profiler\Serializer\SerializerInterface;
use SpiralPackages\Profiler\Serializer\StandartSerializer;

final class FileStorage implements StorageInterface
{
    /**
     * @var string Save to directory.
     */
    private string $dir;

    public function __construct(
        private readonly SerializerInterface $serializer = new StandartSerializer(),
        string $dir = '/tmp',
        private readonly string $extension = 'xhprof'
    ){
        $this->dir = \rtrim($dir, DIRECTORY_SEPARATOR);
    }

    public function store(string $appName, array $tags, \DateTimeInterface $date, array $data): void
    {
        if (!\file_exists($this->dir)) {
            if (!\mkdir(directory: $this->dir, recursive: true)) {
                throw new \RuntimeException(\sprintf('Fail to create folder `%s`', $this->dir));
            }
        }
        $filepath = \sprintf("%s/%s.%s.%s", $this->dir, $appName, $date->getTimestamp(), $this->extension);
        file_put_contents($filepath, $this->serializer->serialize($data));
    }
}
