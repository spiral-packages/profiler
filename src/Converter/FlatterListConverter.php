<?php

declare(strict_types=1);

namespace SpiralPackages\Profiler\Converter;

final class FlatterListConverter implements ConverterInterface
{
    /** @const Key used for methods with no parent */
    private const NO_PARENT = '__top__';

    private array $keys = ['ct', 'wt', 'cpu', 'mu', 'pmu'];

    public function convert(array $data): FlatterList
    {
        $indexed = [];

        $result = [];
        foreach ($data as $name => $values) {
            [$parent, $func] = $this->splitName($name);
            // normalize, fill all missing keys
            $values += [
                'ct' => 0,
                'wt' => 0,
                'cpu' => 0,
                'mu' => 0,
                'pmu' => 0,
            ];

            // Generate collapsed data.
            if (isset($result[$func])) {
                $result[$func] = $this->sumKeys($result[$func], $values);
                $result[$func]['parents'][] = $parent;
            } else {
                $result[$func] = $values;
                $result[$func]['c'] = [$parent];
            }

            // Build the indexed data.
            if ($parent === null) {
                $parent = self::NO_PARENT;
            }
            if (!isset($this->indexed[$parent])) {
                $indexed[$parent] = [];
            }
            $indexed[$parent][$func] = $values;
        }

        unset($values, $name, $parent, $func, $data);

        return new FlatterList(
            $this->keys,
            $result,
            $indexed
        );
    }

    /**
     * Sum up the values in $this->_keys;
     *
     * @param array $a The first set of profile data
     * @param array $b the second set of profile data
     * @return array merged profile data
     */
    private function sumKeys(array $a, array $b): array
    {
        foreach ($this->keys as $key) {
            if (!isset($a[$key])) {
                $a[$key] = 0;
            }
            $a[$key] += $b[$key] ?? 0;
        }

        return $a;
    }

    /**
     * Split a key name into the parent==>child format.
     */
    private function splitName(string $name): array
    {
        $a = \explode('==>', $name);
        if (isset($a[1])) {
            return $a;
        }

        return [null, $a[0]];
    }


}
