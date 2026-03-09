<?php

namespace App\Support\Algorithms;

/**
 * Manual implementation of binary search over a sorted array.
 *
 * We assume the array is sorted by the given key in ascending order.
 */
class BinarySearch
{
    /**
     * Find the index of an item whose $key equals $target.
     *
     * Returns -1 if not found.
     *
     * @param array<int, array<string, mixed>> $items
     */
    public function findIndexByKey(array $items, string $key, int|float $target): int
    {
        $low = 0;
        $high = count($items) - 1;

        while ($low <= $high) {
            $mid = intdiv($low + $high, 2);
            $value = $items[$mid][$key];

            if ($value === $target) {
                return $mid;
            }

            if ($value < $target) {
                $low = $mid + 1;
            } else {
                $high = $mid - 1;
            }
        }

        return -1;
    }
}

