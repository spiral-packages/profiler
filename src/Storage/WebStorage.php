<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Storage;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use SpiralPackages\Profiler\Converter\ConverterInterface;
use SpiralPackages\Profiler\Converter\NullConverter;

final class WebStorage implements StorageInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $endpoint,
        private readonly string $method = 'POST',
        private array $options = [],
        private readonly ConverterInterface $converter = new NullConverter(),
    ) {
    }

    public function store(string $appName, array $tags, \DateTimeInterface $date, array $data): void
    {
        $this->options['json'] = [
            'profile' => $this->converter->convert($data),
            'tags' => $tags,
            'app_name' => $appName,
            'hostname' => \gethostname(),
            'date' => $date->getTimestamp(),
        ];

        $this->httpClient->request($this->method, $this->endpoint, $this->options);
    }
}
