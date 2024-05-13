<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Serializer;

final class StandartSerializer implements SerializerInterface
{
    public function serialize(array $profileData): string
    {
        return \serialize($profileData);
    }
}
