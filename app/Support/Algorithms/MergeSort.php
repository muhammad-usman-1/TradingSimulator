<?php

namespace App\Support\Algorithms;

/**
 * Manual implementation of merge sort for arrays of associative arrays.
 *
 * We avoid using built-in sorting helpers so that the algorithm itself is
 * clearly visible for assessment purposes.
 */
class MergeSort
{
    /**
     * Sort an array of items by a given key using merge sort.
     *
     * @param array<int, array<string, mixed>> $items
     * @return array<int, array<string, mixed>>
     */
    public function sortByKey(array $items, string $key, bool $ascending = true): array
    {
        $count = count($items);

        if ($count <= 1) {
            return $items;
        }

        $middle = intdiv($count, 2);
        $left = [];
        $right = [];

        for ($i = 0; $i < $middle; $i++) {
            $left[] = $items[$i];
        }

        for ($i = $middle; $i < $count; $i++) {
            $right[] = $items[$i];
        }

        $leftSorted = $this->sortByKey($left, $key, $ascending);
        $rightSorted = $this->sortByKey($right, $key, $ascending);

        return $this->merge($leftSorted, $rightSorted, $key, $ascending);
    }

    /**
     * Merge two sorted arrays into one sorted array.
     *
     * @param array<int, array<string, mixed>> $left
     * @param array<int, array<string, mixed>> $right
     * @return array<int, array<string, mixed>>
     */
    private function merge(array $left, array $right, string $key, bool $ascending): array
    {
        $result = [];
        $i = 0;
        $j = 0;
        $leftCount = count($left);
        $rightCount = count($right);

        while ($i < $leftCount && $j < $rightCount) {
            $leftValue = $left[$i][$key];
            $rightValue = $right[$j][$key];

            $takeLeft = $ascending ? ($leftValue <= $rightValue) : ($leftValue >= $rightValue);

            if ($takeLeft) {
                $result[] = $left[$i];
                $i++;
            } else {
                $result[] = $right[$j];
                $j++;
            }
        }

        while ($i < $leftCount) {
            $result[] = $left[$i];
            $i++;
        }

        while ($j < $rightCount) {
            $result[] = $right[$j];
            $j++;
        }

        return $result;
    }
}

