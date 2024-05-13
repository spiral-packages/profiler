<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Serializer;

interface SerializerInterface
{
    public function serialize(array $profileData): string;
}
