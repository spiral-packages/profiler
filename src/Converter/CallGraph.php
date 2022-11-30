<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Converter;

final class CallGraph implements \JsonSerializable
{
    /** @const Key used for methods with no parent */
    private const NO_PARENT = '__top__';

    private array $visited = [];
    private array $links = [];
    private array $nodes = [];

    public function __construct(
        private array $data,
        private readonly array $indexed,
        string $metric,
        float $threshold
    ) {
        $main = $this->data['main()'][$metric];

        $this->callgraphData(self::NO_PARENT, $main, $metric, $threshold);
        $this->data = [
            'metric' => $metric,
            'total' => $main,
            'nodes' => $this->nodes,
            'links' => $this->links,
        ];

        unset($this->visited, $this->nodes, $this->links);
    }

    public function jsonSerialize(): array
    {
        return $this->data;
    }

    private function callgraphData(string $parentName, $main, $metric, $threshold, $parentIndex = null): void
    {
        // Leaves don't have children, and don't have links/nodes to add.
        if (!isset($this->indexed[$parentName])) {
            return;
        }

        $children = $this->indexed[$parentName];
        foreach ($children as $childName => $metrics) {
            $metrics = $this->data[$childName];
            if ($metrics[$metric] / $main <= $threshold) {
                continue;
            }
            $revisit = false;

            // Keep track of which nodes we've visited and their position
            // in the node list.
            if (!isset($this->visited[$childName])) {
                $index = \count($this->nodes);
                $this->visited[$childName] = $index;

                $this->nodes[] = [
                    'name' => $childName,
                    'callCount' => $metrics['ct'],
                    'value' => $metrics[$metric],
                ];
            } else {
                $revisit = true;
                $index = $this->visited[$childName];
            }

            if ($parentIndex !== null) {
                $this->links[] = [
                    'source' => $parentName,
                    'target' => $childName,
                    'callCount' => $metrics['ct'],
                ];
            }

            // If the current function has more children,
            // walk that call subgraph.
            if (isset($this->indexed[$childName]) && !$revisit) {
                $this->callgraphData($childName, $main, $metric, $threshold, $index);
            }
        }
    }
}
