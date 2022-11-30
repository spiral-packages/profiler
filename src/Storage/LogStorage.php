<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Storage;

use Psr\Log\LoggerInterface;
use SpiralPackages\Profiler\Converter\ConverterInterface;
use SpiralPackages\Profiler\Converter\NullConverter;

final class LogStorage implements StorageInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ConverterInterface $converter = new NullConverter(),
    ) {
    }

    public function store(string $appName, array $tags, \DateTimeInterface $date, array $data): void
    {
        $this->logger->info('Profile data', [
            'app' => $appName,
            'tags' => $tags,
            'date' => $date->format('Y-m-d H:i:s'),
            'data' => $this->converter->convert($data),
        ]);
    }
}
