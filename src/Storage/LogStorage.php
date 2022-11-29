<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Storage;

use Psr\Log\LoggerInterface;

final class LogStorage implements StorageInterface
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    public function store(string $appName, array $tags, \DateTimeInterface $date, array $data): void
    {
        $this->logger->info('Profile data', [
            'app' => $appName,
            'tags' => $tags,
            'date' => $date->format('Y-m-d H:i:s'),
            'data' => $data,
        ]);
    }
}
