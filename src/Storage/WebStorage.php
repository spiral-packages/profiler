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
        $tags['hostname'] = \gethostname();

        $this->options['body'] = \json_encode([
            'profile' => $this->converter->convert($data),
            'tags' => $tags,
            'app_name' => $appName,
            'date' => $date->getTimestamp(),
        ]);

        $this->httpClient->request($this->method, $this->endpoint, $this->options);
    }
}
