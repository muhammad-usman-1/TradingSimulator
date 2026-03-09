<?php

namespace App\Support\Collections;

/**
 * Simple manually-implemented list structure.
 *
 * This wraps a plain PHP array but exposes only the operations we need
 * for the simulator and complexity checklist, such as:
 * - append
 * - get/set by index
 * - removeAt
 * - slice
 * - size
 *
 * We deliberately avoid using helpers like array_slice directly in
 * controller code and instead route list-style logic through this class.
 */
class SimpleList
{
    /**
     * Internal storage for list values.
     *
     * @var array<int, mixed>
     */
    private array $items = [];

    /**
     * Append a value to the end of the list.
     */
    public function add(mixed $value): void
    {
        $this->items[] = $value;
    }

    /**
     * Get the value at the given index.
     *
     * @throws \OutOfBoundsException
     */
    public function get(int $index): mixed
    {
        if ($index < 0 || $index >= $this->size()) {
            throw new \OutOfBoundsException("Index {$index} is out of bounds for list of size {$this->size()}.");
        }

        return $this->items[$index];
    }

    /**
     * Replace the value at the given index.
     *
     * @throws \OutOfBoundsException
     */
    public function set(int $index, mixed $value): void
    {
        if ($index < 0 || $index >= $this->size()) {
            throw new \OutOfBoundsException("Index {$index} is out of bounds for list of size {$this->size()}.");
        }

        $this->items[$index] = $value;
    }

    /**
     * Remove the element at the given index and close the gap.
     *
     * @throws \OutOfBoundsException
     */
    public function removeAt(int $index): mixed
    {
        if ($index < 0 || $index >= $this->size()) {
            throw new \OutOfBoundsException("Index {$index} is out of bounds for list of size {$this->size()}.");
        }

        $removed = $this->items[$index];

        // Manually shift elements left so that we are not relying on array_splice.
        for ($i = $index; $i < $this->size() - 1; $i++) {
            $this->items[$i] = $this->items[$i + 1];
        }

        unset($this->items[$this->size() - 1]);

        return $removed;
    }

    /**
     * Return a new SimpleList containing a slice of this list.
     *
     * The slice starts at $start and contains up to $length items (or fewer if the
     * list ends first).
     */
    public function slice(int $start, int $length): SimpleList
    {
        $result = new SimpleList();

        if ($start < 0) {
            $start = 0;
        }

        $end = $start + $length;
        $max = $this->size();

        for ($i = $start; $i < $end && $i < $max; $i++) {
            $result->add($this->items[$i]);
        }

        return $result;
    }

    /**
     * Get the number of elements in the list.
     */
    public function size(): int
    {
        return count($this->items);
    }

    /**
     * Get the last element in the list, or null if empty.
     */
    public function last(): mixed
    {
        $size = $this->size();

        if ($size === 0) {
            return null;
        }

        return $this->items[$size - 1];
    }

    /**
     * Expose the raw array when absolutely necessary (e.g. JSON encoding).
     */
    public function toArray(): array
    {
        return $this->items;
    }
}

