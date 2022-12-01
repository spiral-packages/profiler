<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Converter;

final class Diff implements \JsonSerializable
{
    private readonly array $keys;
    public readonly FlatterList $diff;
    public readonly array $diffPercent;
    private int $functionsCount;
    private float $functionsCountPercent;

    public function __construct(
        private readonly FlatterList $current,
        private readonly FlatterList $head,
    ) {
        $this->keys = $current->getKeys();
        $emptyData = \array_fill_keys($this->keys, 0);

        $diff = [];
        $diffPercent = [];

        foreach ($current->getData() as $key => $baseData) {
            $headData = $head->getData()[$key] ?? $emptyData;
            $diff[$key] = $this->diffKeys($headData, $baseData);

            if ($key === 'main()') {
                $diffPercent[$key] = $this->diffPercentKeys($headData, $baseData);
            }
        }

        $currentCount = $this->getFunctionCount($current);
        $headCount = $this->getFunctionCount($head);

        $this->functionsCount = $headCount - $currentCount;
        $this->functionsCountPercent = $headCount / $currentCount;

        $this->diff = new FlatterList($current->getKeys(), $diff);
        $this->diffPercent = $diffPercent;
    }

    public function jsonSerialize(): array
    {
        return [
            'current' => $this->current,
            'head' => $this->head,
            'diff' => $this->diff,
            'functions_count' => $this->functionsCount,
            'diff_percent' => $this->diffPercent,
            'functions_count_percent' => $this->functionsCountPercent,
        ];
    }


    /**
     * Get the total number of tracked function calls in this run.
     */
    private function getFunctionCount(FlatterList $list): int
    {
        $total = 0;
        foreach ($list->getData() as $data) {
            $total += $data['ct'];
        }

        return $total;
    }

    private function diffKeys(array $a, array $b): array
    {
        $keys = $this->keys;
        foreach ($keys as $key) {
            $a[$key] -= $b[$key];
        }

        return $a;
    }

    private function diffPercentKeys(array $a, array $b): array
    {
        $out = [];
        $keys = $this->keys;

        foreach ($keys as $key) {
            if ($b[$key] !== 0) {
                $out[$key] = $a[$key] / $b[$key];
            } else {
                $out[$key] = -1;
            }
        }

        return $out;
    }
}
