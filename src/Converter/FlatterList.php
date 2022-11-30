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

    public function __construct(
        private readonly array $keys,
        private array $data,
        private readonly array $indexed
    ) {
    }

    public function callGraph(string $metric = 'wt', float $threshold = 0.01): CallGraph
    {
        $valid = $this->keys;
        if (!\in_array($metric, $valid)) {
            throw new \InvalidArgumentException("Unknown metric '$metric'. Cannot generate callgraph.");
        }

        return new CallGraph(
            $this->calculateSelf()->data,
            $this->indexed,
            $metric,
            $threshold
        );
    }

    private function calculateSelf(): self
    {
        $self = clone $this;
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
}
