<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Converter;

/**
 * @psalm-type TResult = array<non-empty-string, array{
 *     ct: int,
 *     wt: int,
 *     cpu: int,
 *     mu: int,
 *     pmu: int,
 *     parents: array<non-empty-string>
 * }>
 */
final class FlatterList implements \JsonSerializable
{
    /** @const Key used for methods with no parent */
    private const NO_PARENT = '__top__';
    private readonly array $indexed;
    private bool $withExclusive;

    public function __construct(
        private readonly array $keys,
        private array $data
    ) {
        $this->withExclusive = false;
        $this->indexData();
    }

    public function diff(self $head): Diff
    {
        return new Diff($this, $head);
    }

    public function getKeys(): array
    {
        $keys = $this->keys;

        if ($this->withExclusive) {
            $keys = \array_merge($keys, ['ewt', 'ecpu', 'emu', 'epmu', 'ect']);
        }

        return $keys;
    }

    public function callGraph(string $metric = 'wt', float $threshold = 0.01): CallGraph
    {
        $valid = $this->getKeys();
        if (!\in_array($metric, $valid)) {
            throw new \InvalidArgumentException("Unknown metric '$metric'. Cannot generate callgraph.");
        }

        return new CallGraph(
            $this->getData(),
            $this->indexed,
            $metric,
            $threshold
        );
    }

    public function withExclusiveValues(): self
    {
        $self = clone $this;
        $self->withExclusive = true;

        // Init exclusive values
        foreach ($self->data as &$data) {
            $data['ewt'] = $data['wt'];
            $data['emu'] = $data['mu'];
            $data['ecpu'] = $data['cpu'];
            $data['ect'] = $data['ct'];
            $data['epmu'] = $data['pmu'];
        }
        unset($data);

        // Go over each method and remove each childs metrics
        // from the parent.
        foreach ($self->data as $name => $data) {
            $children = $self->getChildren($name);
            foreach ($children as $child) {
                $self->data[$name]['ewt'] -= $child['wt'];
                $self->data[$name]['emu'] -= $child['mu'];
                $self->data[$name]['ecpu'] -= $child['cpu'];
                $self->data[$name]['ect'] -= $child['ct'];
                $self->data[$name]['epmu'] -= $child['pmu'];
            }
        }

        return $self;
    }

    private function getChildren(string $symbol, ?string $metric = null, float $threshold = 0.0): array
    {
        $children = [];
        if (!isset($this->indexed[$symbol])) {
            return $children;
        }

        $total = 0;
        if (isset($metric)) {
            $top = $this->indexed[self::NO_PARENT];
            // Not always 'main()'
            $mainFunc = current($top);
            $total = $mainFunc[$metric];
        }

        foreach ($this->indexed[$symbol] as $name => $data) {
            if (
                $metric && $total > 0 && $threshold > 0 &&
                ($this->data[$name][$metric] / $total) < $threshold
            ) {
                continue;
            }
            $children[] = $data + ['function' => $name];
        }

        return $children;
    }

    /**
     * @return TResult
     */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    /**
     * @return TResult
     */
    public function getData(): array
    {
        return $this->data;
    }

    private function indexData(): void
    {
        $indexed = [];

        foreach ($this->data as $key => $data) {
            $parents = $data['parents'] ?? [];
            unset($data['parents']);
            foreach ($parents as $parent) {
                $indexed[$parent][$key] = $data;
            }
        }

        $this->indexed = $indexed;
    }
}
